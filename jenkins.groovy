pipeline {
agent any
    options {
        buildDiscarder(logRotator(numToKeepStr: '3'))
    }
    stages {
        stage('GitHub') {
            steps {
                slackSend (color: '#FFFF00', message: "EMPEZO: Tarea '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
                echo "Clonacion del Proyecto en GitHub"
                checkout([$class: 'GitSCM', branches: [[name: '*/master']], doGenerateSubmoduleConfigurations: false, extensions: [], submoduleCfg: [], userRemoteConfigs: [[credentialsId: '', url: 'https://github.com/safernandez666/DevSecOps.git']]])
            }
        }
        stage('SAST') {
            parallel {
                stage('Check GitLeaks') {
                    steps { 
                    echo "Analisis Leaks"       
                        script {
                            int code = sh returnStatus: true, script: """ gitleaks --repo-path=$PWD/workspace/$JOB_NAME --verbose --pretty --config=gitleaks.toml """
                            if(code==1) {
                                currentBuild.result = 'FAILURE'
                                error('Contraseñas en el Codigo.')
                                println "UNESTABLE"
                            }
                            else {
                                currentBuild.result = 'SUCCESS' 
                                println "Sin Contraseñas en el Codigo."
                                println "SUCCESS"
                            }   
                        }         
                    }
                }
                stage('Dependency Check') {
                    steps {
                        echo "Analisis de Dependencias"
                        sh 'sh /opt/dependency-check/bin/dependency-check.sh --scan /var/lib/jenkins/workspace/$JOB_NAME --format ALL --nodeAuditSkipDevDependencies --disableNodeJS'
                        dependencyCheckPublisher pattern: '**/dependency-check-report.xml'
                        sleep(time:5,unit:"SECONDS")
                    }
                }
                stage('SonarQube') {
                    steps {
                        echo "Analisis SonarQube"
                        sh "/opt/sonar-scanner/bin/sonar-scanner -Dsonar.host.url=http://pipeline.ironbox.com.ar:9000 -Dsonar.projectName=DevSecOpsAppVul -Dsonar.projectVersion=1.0 -Dsonar.projectKey=DevSecOpsAppVul -Dsonar.sources=. -Dsonar.projectBaseDir=/var/lib/jenkins/workspace/$JOB_NAME/VulnerableApp"
                        sleep(time:10,unit:"SECONDS")
                    }
                }
            }
        }
        stage('Copia Fuentes') {
            steps {
                echo "Copia Fuentes a Dockerfile"
                sh 'cp -avr $PWD/* /opt/docker/appvul'  
            }
        }
        stage('Generar Build de Docker & Eliminar Conteneradores e Imagines') {
            steps {
                echo "Copia Fuentes a Dockerfile"
                sh """ 
                cd /opt/docker/appvul
                docker build -t appvul .
                docker tag appvul:latest safernandez666/appvul:latest
                docker push safernandez666/appvul:latest
                docker rmi appvul safernandez666/appvul
                """  
            }
        }
        stage('Scan Docker MicroAqua'){
            steps {
                //aquaMicroscanner imageName: 'safernandez666/nginx:latest', notCompliesCmd: 'exit 1', onDisallowed: 'fail'
                aquaMicroscanner imageName: 'safernandez666/appvul:latest', notCompliesCmd: '', onDisallowed: 'ignore', outputFormat: 'html'
            }
        }
        stage('Deploy en Nodos de Ansible') {
            steps {
                sh """ 
                cd /opt/playbooks
                ansible-playbook -i /opt/ansible/hosts createAppvul.yml --private-key /opt/ansible/DevSecOps.pem -u ubuntu 
                """ 
            }
        }
        stage('DAST') {
            steps {
                script {
                    //sh "docker exec zap zap-cli --verbose quick-scan http://pipeline.ironbox.com.ar:8090 -l Medium" 
                    try {
                        echo "Inicio de Scanneo Dinamico"
                        sh "docker exec zap zap-cli --verbose quick-scan http://pipeline.ironbox.com.ar:8090 -l Medium" 
                        //sh "docker exec zap zap-cli --verbose alerts --alert-level Medium -f json | jq length"
                        currentBuild.result = 'SUCCESS' 
                    }
                    catch (Exception e) {
                            //echo e.getMessage() 
                            //currentBuild.result = 'FAILURE'
                            println ("Revisar Reporte ZAP. Se encontraron Vulnerabilidades.")

                        }
                    }  
                    echo currentBuild.result 
                    echo "Generacion de Reporte"
                    sh "docker exec zap zap-cli --verbose report -o /zap/reports/owasp-quick-scan-report.html --output-format html"
                    publishHTML target: [
                        allowMissing: false,
                        alwaysLinkToLastBuild: false,
                        keepAll: true,
                        reportDir: '/opt/dast/reports',
                        reportFiles: 'owasp-quick-scan-report.html',
                        reportName: 'Analisis DAST'
                      ]          
            }
        }
}
    post('Notificaciones') {
        success {
          slackSend (color: '#00FF00', message: "EXITO: Tarea '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
        }
      failure {
        slackSend (color: '#FF0000', message: "FALLO: Tarea '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
        }
    }
}
