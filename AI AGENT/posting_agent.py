import tweepy
import datetime
import os
import socket
import time

# ========== Step 1: Wait for internet connection ==========
def is_connected():
    try:
        socket.create_connection(("api.twitter.com", 443), timeout=5)
        return True
    except OSError:
        return False

for _ in range(120):
    if is_connected():
        break
    time.sleep(1)
else:
    print("❌ Internet not available. Tweet not sent.")
    exit()

# ========== Step 2: Skip if already posted today ==========
today = datetime.datetime.now().strftime("%Y-%m-%d")
weekday = datetime.datetime.now().strftime("%A")

log_file = "last_posted.txt"
if os.path.exists(log_file):
    with open(log_file, "r") as f:
        last_date = f.read().strip()
    if last_date == today:
        print("✅ Already posted today. Skipping.")
        exit()

# ========== Step 3: Prepare image and caption ==========
quotes = {
    "Monday": "Believe you can and you're halfway there.",
    "Tuesday": "Push yourself, because no one else will do it for you.",
    "Wednesday": "Success is not for the lazy.",
    "Thursday": "Stay positive, work hard, make it happen.",
    "Friday": "It always seems impossible until it's done.",
    "Saturday": "Don't stop until you're proud.",
    "Sunday": "Take time to make your soul happy."
}
quote = quotes.get(weekday, "Stay inspired.")

image_folder = "inspiration_images"
image_path = os.path.join(image_folder, f"{weekday}.jpg")  # Ex: inspiration_images/Monday.jpg

if not os.path.exists(image_path):
    print(f"❌ Image for {weekday} not found at {image_path}")
    exit()

# ========== Step 4: Twitter API credentials ==========
API_KEY = 'Kn1l7LJdIsKpY3fQVMKwkmUjr'
API_SECRET_KEY = 'FPX0yFvEPizLimZK0s5BV4GCYoSMz1SjF0B7qKZuentCq4w0Ki'
ACCESS_TOKEN = '1764936419951132672-I0Tsq99pAKjGjjZO49KDVEw2EUm8FB'
ACCESS_TOKEN_SECRET = 'WWINpORxkew71rDdfzdW8wzZRol7xosnStn4q3zXfPAEC'

# ========== Step 5: Post the image with caption ==========
auth = tweepy.OAuth1UserHandler(API_KEY, API_SECRET_KEY, ACCESS_TOKEN, ACCESS_TOKEN_SECRET)
api = tweepy.API(auth)

try:
    media = api.media_upload(image_path)
    post = api.update_status(status=quote, media_ids=[media.media_id])
    print(f"✅ Posted image with quote: {quote}")
    with open(log_file, "w") as f:
        f.write(today)
except Exception as e:
    print(f"❌ Failed to post image tweet: {e}")
