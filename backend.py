from flask import Flask, request, jsonify
import joblib
import re
import string
import os
from mail import classify_email, train_and_save_model  # Import the necessary functions

app = Flask(__name__)

# Check if the model and vectorizer files exist, if not, train and save them
model_path = 'model.pkl'
vectorizer_path = 'vectorizer.pkl'

if not os.path.exists(model_path) or not os.path.exists(vectorizer_path):
    print("Model or vectorizer not found. Training the model...")
    train_and_save_model()  # Call the function in mail.py to train and save the model
else:
    print("Model and vectorizer found. Loading the model...")

# Load the trained model and vectorizer
model = joblib.load(model_path)
vectorizer = joblib.load(vectorizer_path)

def clean_text(text):
    """Function to clean the text (remove punctuation, lowercase)."""
    text = text.lower()
    text = re.sub(f"[{string.punctuation}]", "", text)
    return text.strip()

@app.route('/classify', methods=['POST'])
def classify():
    # Extract the email content from the POST request
    email = request.json['email']
    
    # Call the classify_email function from mail.py for classification
    result = classify_email(email)  # This will return 'Spam' or 'Not Spam'

    # Return the result as a JSON response
    return jsonify({"result": result})

if __name__ == "__main__":
    app.run(debug=True, host='0.0.0.0', port=5000)

