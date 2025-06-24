from flask import Flask
from flask_sqlalchemy import SQLAlchemy
import os
from datetime import datetime

db = SQLAlchemy()

def create_app():
    # Always remove the DB file on startup
    db_path = '/tmp/xsslab.db'
    if os.path.exists(db_path):
        os.remove(db_path)
    app = Flask(__name__)
    app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:////tmp/xsslab.db'
    app.config['SECRET_KEY'] = 'devkey'
    db.init_app(app)

    @app.context_processor
    def inject_year():
        return {'year': datetime.now().year}

    with app.app_context():
        from . import routes
        db.create_all()
    return app 