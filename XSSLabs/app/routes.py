from flask import render_template, request, redirect, url_for, session, make_response, flash
from . import db
from flask import current_app as app
from datetime import datetime
import re

# In-memory logs for blind XSS and advanced challenges
blind_xss_logs = []
cookie_theft_logs = []

@app.route('/')
def index():
    return render_template('index.html')

# --- Stored XSS ---
class Comment(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    content = db.Column(db.Text, nullable=False)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)

@app.route('/stored', methods=['GET', 'POST'])
def stored_xss():
    if request.method == 'POST':
        content = request.form.get('content', '')
        db.session.add(Comment(content=content))
        db.session.commit()
        return redirect(url_for('stored_xss'))
    comments = Comment.query.order_by(Comment.created_at.desc()).all()
    return render_template('stored_xss.html', comments=comments)

# --- Reflected XSS ---
@app.route('/reflected', methods=['GET', 'POST'])
def reflected_xss():
    query = ''
    result = None
    if request.method == 'POST':
        query = request.form.get('query', '')
        result = query  # Reflected unsanitized
    return render_template('reflected_xss.html', query=query, result=result)

# --- Blind XSS ---
class BlindPayload(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    payload = db.Column(db.Text, nullable=False)
    timestamp = db.Column(db.DateTime, default=datetime.utcnow)
    visited = db.Column(db.Boolean, default=False)

@app.route('/blind', methods=['GET', 'POST'])
def blind_xss():
    if request.method == 'POST':
        payload = request.form.get('payload', '')
        db.session.add(BlindPayload(payload=payload))
        db.session.commit()
        flash('Payload submitted! If it triggers, you will see a log below.')
    logs = BlindPayload.query.order_by(BlindPayload.timestamp.desc()).all()
    return render_template('blind_xss.html', logs=logs)

@app.route('/api/blind_payloads', methods=['GET'])
def api_blind_payloads():
    unvisited = BlindPayload.query.filter_by(visited=False).all()
    return {'payloads': [{'id': p.id, 'payload': p.payload} for p in unvisited]}

@app.route('/api/mark_visited/<int:payload_id>', methods=['POST'])
def api_mark_visited(payload_id):
    p = BlindPayload.query.get(payload_id)
    if p:
        p.visited = True
        db.session.commit()
        return {'status': 'ok'}
    return {'status': 'not found'}, 404

@app.route('/admin/blind_xss', methods=['GET'])
def admin_blind_xss():
    # Simulate admin visiting payloads (for bot)
    for entry in blind_xss_logs:
        # In a real bot, this would render the payload in a browser
        pass
    return 'Admin visited blind XSS payloads.'

# --- Advanced: When Tools Fail (Cookie Theft) ---
class AdvancedPayload1(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    payload = db.Column(db.Text, nullable=False)
    timestamp = db.Column(db.DateTime, default=datetime.utcnow)
    visited = db.Column(db.Boolean, default=False)

class AdvancedPayload2(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    payload = db.Column(db.Text, nullable=False)
    timestamp = db.Column(db.DateTime, default=datetime.utcnow)
    visited = db.Column(db.Boolean, default=False)

class CookieTheftLog(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    cookie = db.Column(db.Text, nullable=False)
    timestamp = db.Column(db.DateTime, default=datetime.utcnow)

def filter_2(payload):
    regex = r".*(script|(</.*>)).*"
    if re.search(regex, payload, re.IGNORECASE):
        return "Nope"
    return payload

@app.route('/when_tools_fail1', methods=['GET', 'POST'])
def when_tools_fail1():
    if 'admin' in request.args:
        resp = make_response(render_template('when_tools_fail1.html', logs=[], cookies=[]))
        resp.set_cookie('admin_session', 'BLSOps')
        return resp
    if request.method == 'POST':
        payload = request.form.get('payload', '')
        filtered = filter_2(payload)
        if filtered == "Nope":
            flash(f'Nope. Blocked payload: {payload}')
        else:
            db.session.add(AdvancedPayload1(payload=filtered))
            db.session.commit()
    logs = AdvancedPayload1.query.order_by(AdvancedPayload1.timestamp.desc()).all()
    cookies = CookieTheftLog.query.order_by(CookieTheftLog.timestamp.desc()).all()
    return render_template('when_tools_fail1.html', logs=logs, cookies=cookies)

@app.route('/api/advanced_payloads', methods=['GET'])
def api_advanced_payloads():
    unvisited = AdvancedPayload1.query.filter_by(visited=False).all()
    return {'payloads': [{'id': p.id, 'payload': p.payload} for p in unvisited]}

@app.route('/api/mark_advanced_visited/<int:payload_id>', methods=['POST'])
def api_mark_advanced_visited(payload_id):
    p = AdvancedPayload1.query.get(payload_id)
    if p:
        p.visited = True
        db.session.commit()
        return {'status': 'ok'}
    return {'status': 'not found'}, 404

@app.route('/exfiltrate', methods=['GET'])
def exfiltrate():
    cookie = request.args.get('cookie', '')
    if cookie:
        db.session.add(CookieTheftLog(cookie=cookie))
        db.session.commit()
    return '', 204

@app.route('/admin')
def admin_panel():
    admin_cookie = request.cookies.get('admin_session')
    if admin_cookie == 'BBOTisAwesome' or admin_cookie == 'BLSOps':
        return render_template('admin_panel.html', admin_cookie=admin_cookie)
    # Render a themed unauthorized page
    return render_template('unauthorized.html'), 403

@app.route('/dom_xss')
def dom_xss():
    return render_template('dom_xss.html')

def filter_3(payload):
    regex = r".*(://|script|(</.*>)).*"
    if re.search(regex, payload, re.IGNORECASE):
        return "Nope"
    return payload

@app.route('/when_tools_fail2', methods=['GET', 'POST'])
def when_tools_fail2():
    if 'admin' in request.args:
        resp = make_response(render_template('when_tools_fail2.html', logs=[], cookies=[]))
        resp.set_cookie('admin_session', 'TrevorSpray')
        return resp
    if request.method == 'POST':
        payload = request.form.get('payload', '')
        filtered = filter_3(payload)
        if filtered == "Nope":
            flash(f'Nope. Blocked payload: {payload}')
        else:
            db.session.add(AdvancedPayload2(payload=filtered))
            db.session.commit()
    logs = AdvancedPayload2.query.order_by(AdvancedPayload2.timestamp.desc()).all()
    cookies = CookieTheftLog.query.order_by(CookieTheftLog.timestamp.desc()).all()
    return render_template('when_tools_fail2.html', logs=logs, cookies=cookies)

@app.route('/api/advanced_payloads2', methods=['GET'])
def api_advanced_payloads2():
    unvisited = AdvancedPayload2.query.filter_by(visited=False).all()
    return {'payloads': [{'id': p.id, 'payload': p.payload} for p in unvisited]}

@app.route('/api/mark_advanced_visited2/<int:payload_id>', methods=['POST'])
def api_mark_advanced_visited2(payload_id):
    p = AdvancedPayload2.query.get(payload_id)
    if p:
        p.visited = True
        db.session.commit()
        return {'status': 'ok'}
    return {'status': 'not found'}, 404 