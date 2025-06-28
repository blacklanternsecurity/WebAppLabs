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

