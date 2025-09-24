# Concept Medical Software
Patient and Doctor management system

# STEP:1
composer install

# STEP:2
create database and copy .env.example and rename with .env

# STEP:3
php artisan migrate

# STEP:4
npm install

# STEP:5
npm run build

# STEP:6
php artisan db:seed 

# STEP:7
php artisan serve

Email: niru@codeinmindinfotech.com
Password: 123456

# Note
php artisan generate:permissions {{doctor}} 

{{doctor}} controller name for create permission

# Command
for run Event on queue
php artisan queue:work


# use Trait to get value from dropdown
$methods = $this->getDropdownOptions('CONTACT_METHODS');

# run when first time
composer dump-autoload


# 🎙️ Audio-to-Text Transcription on DigitalOcean with Laravel & Whisper

This guide explains how to **create an audio-to-text transcription feature** on a **DigitalOcean server**, using tools like **Python**, **FFmpeg**, and **OpenAI Whisper**.


## 🎯 Objective

Transcribe uploaded audio files into text using [OpenAI Whisper](https://github.com/openai/whisper), integrated within a Laravel application hosted on a DigitalOcean droplet.


## 🧰 Prerequisites

- ✅ A **DigitalOcean droplet** (Ubuntu 20.04+)
- ✅ Laravel app already deployed
- ✅ `python3`, `pip`, `ffmpeg`, and `git` installed
- ✅ Audio upload form already exists in your Laravel app


## 🧾 Step-by-Step: Server-Side Setup

### 🛠️ Step 1: SSH into Your DigitalOcean Server

```bash
ssh root@your-server-ip
````


### 🐍 Step 2: Install Dependencies

#### 🔹 Python 3 & Virtual Environment

```bash
sudo apt update
sudo apt install python3 python3-pip python3-venv -y
python3 --version  # Example: Python 3.10.9
```

#### 🔹 FFmpeg (for audio conversion)

```bash
sudo apt install ffmpeg -y
which ffmpeg  # Should output: /usr/bin/ffmpeg
```

#### 🔹 Git (to clone Whisper)

```bash
sudo apt install git -y
```


### 📦 Step 3: Create Python Virtual Environment

```bash
cd /var/www/laravelapp/concept_medical_software
python3 -m venv whisper_env
source whisper_env/bin/activate
```

> 🧠 Using a virtual environment keeps dependencies isolated from the system Python.


### 🤖 Step 4: Install Whisper in Virtual Environment

```bash
pip install --upgrade pip setuptools wheel
pip install git+https://github.com/openai/whisper.git
```

Verify it's installed:

```bash
whisper --help
```


### ⚙️ Step 5: Set Paths in Laravel `.env`

Update your `.env` file:

```env
PYTHON_PATH=/var/www/laravelapp/concept_medical_software/whisper_env/bin/python
FFMPEG_PATH=/usr/bin/ffmpeg
```

Clear and cache the config:

```bash
php artisan config:clear
php artisan config:cache
```


### 🔧 Step 6: Laravel Controller Example

Make sure your controller builds the transcription command correctly:

```php
$command = "\"$pythonPath\" -m whisper \"$fullWavPath\" --model tiny --language English --output_format txt --output_dir \"$outputDir\"";
```

Use `Process` or `exec()` to run the command and capture the output.


## 📁 File & Folder Permissions

Ensure Laravel has the right permissions to:

* Save audio files to `storage/app/public/audio`
* Run Python and FFmpeg

Run:

```bash
sudo chown -R www-data:www-data /var/www/laravelapp
sudo chmod -R 775 /var/www/laravelapp/storage
```


## 🧪 Optional: Test Whisper Manually

Upload a sample audio file to your server and run:

```bash
source whisper_env/bin/activate
whisper /full/path/to/audio.mp3 --model tiny --language English --output_format txt
```

If this works, your Laravel integration should also work correctly.


## 🔁 End-to-End Workflow

1. **User uploads audio via form**
2. Laravel saves the uploaded file
3. Laravel uses **FFmpeg** to convert it to WAV
4. Laravel runs **Whisper (Python)** to transcribe the WAV
5. Laravel saves the transcription text to the database


## 📌 Notes

* Whisper models: `tiny`, `base`, `small`, `medium`, `large`
  Use `tiny` for faster results; use `base`+ for higher accuracy.
* You can enqueue transcription with Laravel Jobs for heavy loads.


> ✅ If you need Whisper to run faster or use less memory, consider exploring [whisper.cpp](https://github.com/ggerganov/whisper.cpp).


## 📫 Need Help?

Feel free to open issues or submit pull requests if you're improving this integration!

```
