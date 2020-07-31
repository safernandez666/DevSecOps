import requests
import json
from datetime import datetime

url = 'http://XX.XX.XX.XX:8080/api/v2/import-scan/'

headers = {'Authorization': 'Token XXXXXXXXXXXXX'}

files = {'file': open('/Users/santiago/Desktop/dependency-check-report.xml','rb')}

dia = datetime.today().strftime('%Y-%m-%d')
print(dia)

body = {'scan_date': dia,
'scan_type': 'Dependency Check Scan',
'engagement': '1',
'lead': '1'
}

r = requests.post(url, headers=headers, files=files, verify=False, data=body)

print("The response status code :%s"%r.status_code)
print("The response text is :%s"%r.text)
