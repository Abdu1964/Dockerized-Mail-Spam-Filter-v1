import pandas as pd
import re
import string
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
import joblib


def clean_text(text):
        """Preprocess text by cleaning punctuation and converting to lowercase."""
        text = text.lower()
        text = re.sub(f"[{string.punctuation}]", "", text)
        return text.strip()
# Function to check and train the model if necessary
def train_and_save_model():
    # Load dataset (this step is required for training)
    file_path = "sms-spam-collection.tsv"
    df = pd.read_csv(file_path, sep='\t', usecols=[0, 1], names=["label", "text"], on_bad_lines="skip", encoding="utf-8")
    df["label"] = df["label"].map({"ham": 0, "spam": 1})
    df.dropna(subset=["label", "text"], inplace=True)

    df["clean_text"] = df["text"].apply(clean_text)

    # Convert text into numerical features using TF-IDF
    vectorizer = TfidfVectorizer(stop_words="english")
    X = vectorizer.fit_transform(df["clean_text"])
    y = df["label"]
    
    # Split dataset
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

    # Train Logistic Regression model
    model = LogisticRegression(max_iter=200)
    model.fit(X_train, y_train)

    # Save the model and vectorizer
    joblib.dump(model, 'model.pkl')
    joblib.dump(vectorizer, 'vectorizer.pkl')

# Function to classify a new email
def classify_email(email):
    """Classify the provided email as Spam or Not Spam."""
    # Load the model and vectorizer
    model = joblib.load('model.pkl')
    vectorizer = joblib.load('vectorizer.pkl')
    
    # Preprocess email
    email_cleaned = clean_text(email)
    email_vectorized = vectorizer.transform([email_cleaned])
    
    # Prediction
    prediction = model.predict(email_vectorized)[0]
    return "Spam" if prediction == 1 else "Not Spam"
