from flask import Flask, render_template, request, redirect, url_for, session
import sqlite3
from werkzeug.security import generate_password_hash, check_password_hash

app = Flask(__name__)
app.secret_key = 'your_secret_key'

# Database setup (create table if not exists)
def init_db():
    conn = sqlite3.connect('database.db')
    cursor = conn.cursor()
    cursor.execute('''
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        status TEXT DEFAULT 'pending'
    )
    ''')
    conn.commit()
    conn.close()

# Routes
@app.route('/')
def home():
    return "Welcome! Go to /register or /login to proceed."

# Registration Route
@app.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        name = request.form['name']
        password = generate_password_hash(request.form['password'])
        conn = sqlite3.connect('database.db')
        cursor = conn.cursor()
        try:
            cursor.execute("INSERT INTO users (name, password) VALUES (?, ?)", (name, password))
            conn.commit()
            conn.close()
            return "Registration successful. Your status is 'pending'."
        except sqlite3.IntegrityError:
            return "User already exists. Try a different name."
    return render_template('register.html')

# Login Route
@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        name = request.form['name']
        password = request.form['password']
        conn = sqlite3.connect('database.db')
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM users WHERE name=?", (name,))
        user = cursor.fetchone()
        conn.close()

        if user and check_password_hash(user[2], password):  # user[2] is the hashed password
            session['user_id'] = user[0]
            session['status'] = user[3]
            if user[3] == 'pending':
                return redirect(url_for('pending'))
            elif user[3] == 'approved':
                return redirect(url_for('approved'))
        return "Invalid credentials."
    return render_template('login.html')

# Pending Page
@app.route('/pending')
def pending():
    if session.get('status') != 'pending':
        return redirect(url_for('approved'))
    return "Your request is pending. Please wait for admin approval."

# Approved Page
@app.route('/approved')
def approved():
    if session.get('status') != 'approved':
        return redirect(url_for('pending'))
    return "Congratulations! Your account is approved."

# Admin Login
@app.route('/admin_login', methods=['GET', 'POST'])
def admin_login():
    if request.method == 'POST':
        admin_password = request.form['password']
        if admin_password == "FAIZU_H3R3":  # Admin password
            session['admin'] = True
            return redirect(url_for('admin_dashboard'))
        return "Invalid admin password."
    return render_template('admin_login.html')

# Admin Dashboard
@app.route('/admin_dashboard')
def admin_dashboard():
    if not session.get('admin'):
        return redirect(url_for('admin_login'))
    
    conn = sqlite3.connect('database.db')
    cursor = conn.cursor()
    cursor.execute("SELECT * FROM users WHERE status='pending'")
    users = cursor.fetchall()
    conn.close()
    return render_template('admin_dashboard.html', users=users)

# Approve User
@app.route('/approve/<int:user_id>')
def approve(user_id):
    if not session.get('admin'):
        return redirect(url_for('admin_login'))
    
    conn = sqlite3.connect('database.db')
    cursor = conn.cursor()
    cursor.execute("UPDATE users SET status='approved' WHERE id=?", (user_id,))
    conn.commit()
    conn.close()
    return redirect(url_for('admin_dashboard'))

# Initialize Database
init_db()

if __name__ == '__main__':
    app.run(debug=True, port=5000)
