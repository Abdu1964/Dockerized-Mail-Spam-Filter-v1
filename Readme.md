# **Dockerized Mail Spam Filter v1**

## **Overview**

This project focuses on **dockerizing** a **machine learning-based spam filter** using **Logistic Regression**. The backend is developed with **Flask**, and the frontend is integrated into **WordPress**. The entire application is containerized using **Docker Compose**, enabling seamless deployment of both the Flask API and WordPress frontend with minimal configuration.

## **Core Feature: Dockerization**

The main objective of this project is to **dockerize** the spam filter application. The backend, which uses Flask for the **Logistic Regression** model, is packaged into a Docker container, along with the WordPress environment. This allows easy deployment of the **Flask API** for spam classification, alongside a WordPress frontend, all within a unified containerized environment.

### **Docker Compose Setup**

Using **Docker Compose**, this project manages multiple containers, including:

- A **Flask container** for the backend machine learning model and API.
- A **WordPress container** with a custom plugin that interfaces with the Flask API to provide spam detection in the WordPress frontend.

## **Setup & Deployment with Docker Compose**

Follow the steps below to set up and run the application:

### 1. **Clone the Repository**

First, clone the repository to your local machine:

```bash
git clone https://github.com/Abdu1964/Dockerized-Mail-Spam-Filter-v1.git
cd Dockerized-Mail-Spam-Filter-v1
```


### 2. **Build and Start the Containers**

Run the following command to build and start the containers using Docker Compose:
```bash
docker build -t <your docker image name> .
```
## Next 

```bash
docker-compose up -d
```

### 3. **Access the Application**
 
Once the containers are up and running, access the following:

## to access the flask api with the spam filtering plugin.
Flask API: You can interact with the Flask API at http://localhost:5000/classify for spam classification requests.

## to access  WordPress Frontend: Navigate to
```bash
http://localhost:8080
```


### 4. **Flask API Integration with WordPress**

The integration between the Flask API and the WordPress frontend is achieved through a custom plugin, which includes both PHP and CSS files. The plugin makes it possible to use the Flask API for spam classification directly within the WordPress interface.

You can access the integration in the public repository here:

wordpress/wp-content/plugins/email-spam-classifier