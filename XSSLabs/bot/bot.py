import time
import requests
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from urllib.parse import unquote

WEB_URL = 'http://web:5000'  # Docker Compose service name
API_PAYLOADS_URL = f'{WEB_URL}/api/blind_payloads'
API_MARK_VISITED_URL = f'{WEB_URL}/api/mark_visited/'
ADMIN_PANEL_URL = f'{WEB_URL}/admin'
API_ADVANCED_PAYLOADS = f'{WEB_URL}/api/advanced_payloads'
API_MARK_ADVANCED_VISITED = f'{WEB_URL}/api/mark_advanced_visited/'
API_ADVANCED_PAYLOADS2 = f'{WEB_URL}/api/advanced_payloads2'
API_MARK_ADVANCED_VISITED2 = f'{WEB_URL}/api/mark_advanced_visited2/'

def get_blind_xss_payloads():
    try:
        resp = requests.get(API_PAYLOADS_URL)
        if resp.status_code != 200:
            return []
        return resp.json().get('payloads', [])
    except Exception as e:
        print(f'Error fetching payloads: {e}')
        return []

def mark_payload_visited(payload_id):
    try:
        requests.post(f'{API_MARK_VISITED_URL}{payload_id}')
    except Exception as e:
        print(f'Error marking payload {payload_id} as visited: {e}')

def visit_payload(payload, payload_id):
    payload = unquote(payload)
    options = Options()
    options.add_argument('--headless')
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')
    driver = webdriver.Chrome(options=options)
    try:
        driver.get(WEB_URL)
        driver.add_cookie({'name': 'admin_session', 'value': 'BBOTisAwesome', 'path': '/'})
        driver.get(f'{WEB_URL}/blind')
        if payload.strip().startswith('<'):
            driver.execute_script("document.body.innerHTML += arguments[0];", payload)
        else:
            driver.execute_script(payload)
        print(f'Visited payload: {payload}')
        time.sleep(2)
        mark_payload_visited(payload_id)
    except Exception as e:
        print(f'Error visiting payload: {payload} - {e}')
    finally:
        driver.quit()

def get_advanced_payloads():
    try:
        resp = requests.get(API_ADVANCED_PAYLOADS)
        if resp.status_code != 200:
            return []
        return resp.json().get('payloads', [])
    except Exception as e:
        print(f'Error fetching advanced payloads: {e}')
        return []

def mark_advanced_visited(payload_id):
    try:
        requests.post(f'{API_MARK_ADVANCED_VISITED}{payload_id}')
    except Exception as e:
        print(f'Error marking advanced payload {payload_id} as visited: {e}')

def visit_advanced_payload(payload, payload_id):
    payload = unquote(payload)
    options = Options()
    options.add_argument('--headless')
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')
    driver = webdriver.Chrome(options=options)
    try:
        driver.get(WEB_URL)  # Load home page to set cookie first
        driver.add_cookie({'name': 'admin_session', 'value': 'BLSOps', 'path': '/'})
        driver.get(f'{WEB_URL}/when_tools_fail1?admin=1')
        if payload.strip().startswith('<'):
            driver.execute_script("document.body.innerHTML += arguments[0];", payload)
        else:
            driver.execute_script(payload)
        print(f'Visited advanced payload: {payload}')
        time.sleep(2)
    except Exception as e:
        print(f'Error visiting advanced payload: {payload} - {e}')
    finally:
        mark_advanced_visited(payload_id)
        driver.quit()

def get_advanced_payloads2():
    try:
        resp = requests.get(API_ADVANCED_PAYLOADS2)
        if resp.status_code != 200:
            return []
        return resp.json().get('payloads', [])
    except Exception as e:
        print(f'Error fetching advanced payloads 2: {e}')
        return []

def mark_advanced_visited2(payload_id):
    try:
        requests.post(f'{API_MARK_ADVANCED_VISITED2}{payload_id}')
    except Exception as e:
        print(f'Error marking advanced payload 2 {payload_id} as visited: {e}')

def visit_advanced_payload2(payload, payload_id):
    payload = unquote(payload)
    options = Options()
    options.add_argument('--headless')
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')
    driver = webdriver.Chrome(options=options)
    try:
        driver.get(WEB_URL)  # Load home page to set cookie first
        driver.add_cookie({'name': 'admin_session', 'value': 'TrevorSpray', 'path': '/'})
        driver.get(f'{WEB_URL}/when_tools_fail2?admin=1')
        if payload.strip().startswith('<'):
            driver.execute_script("document.body.innerHTML += arguments[0];", payload)
        else:
            driver.execute_script(payload)
        print(f'Visited advanced payload 2: {payload}')
        time.sleep(2)
    except Exception as e:
        print(f'Error visiting advanced payload 2: {payload} - {e}')
    finally:
        mark_advanced_visited2(payload_id)
        driver.quit()

def main():
    print('Admin bot starting. Polling for blind XSS and advanced payloads...')
    while True:
        payloads = get_blind_xss_payloads()
        for p in payloads:
            visit_payload(p['payload'], p['id'])
        advanced_payloads = get_advanced_payloads()
        for p in advanced_payloads:
            visit_advanced_payload(p['payload'], p['id'])
        advanced_payloads2 = get_advanced_payloads2()
        for p in advanced_payloads2:
            visit_advanced_payload2(p['payload'], p['id'])
        time.sleep(30)

if __name__ == '__main__':
    main() 