timestamps {

node () {

	stage ('Checkout') {
 	 checkout([$class: 'GitSCM', branches: [[name: '*/master']], doGenerateSubmoduleConfigurations: false, extensions: [], submoduleCfg: [], userRemoteConfigs: [[credentialsId: '', url: 'https://github.com/safernandez666/Nginx']]]) 
	}
	stage ('Copiar Archivos a Ansible') {
		// Shell build step
		sh """ 
		cp src/index.php /opt/docker/nginx
		cp Dockerfile /opt/docker/nginx
		 """		// Shell build step
    }
	stage ('Generar Build de Docker & Eliminar Conteneradores e Imagines') {
		sh """ 
		cd /opt/docker/nginx
		docker build -t nginx .
		docker tag nginx:latest safernandez666/nginx:latest
		docker push safernandez666/nginx:latest
		docker rmi nginx safernandez666/nginx 
		 """		// Shell build step
    }	
	stage ('Deploy en Nodos de Ansible') {
		sh """ 
		cd /opt/playbooks
		ansible-playbook -i /opt/ansible/hosts createNginx.yml --private-key /opt/ansible/DevSecOps.pem -u ubuntu 
		 """ 
    }	
  }
}
