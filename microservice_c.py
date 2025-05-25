"""
Microservice C – Fetch first Google Image result for a prompt (file-bus edition)
-------------------------------------------------------------------------------
• Watches   bus/ai_in.txt   for JSON requests:
• Uses Google Custom Search JSON API to get the first image result for the prompt
• Writes a JSON response line to   bus/ai_out.txt :
  or on failure:

Requires an environment file (.env) at project root with:
GOOGLE_API_KEY=your_api_key
GOOGLE_CX=your_search_engine_id

Install dependencies:
pip install requests python-dotenv

Run:
python3 microservice_c_google_image.py
Stop: Ctrl-C
"""
import json, time, sys
import requests
from pathlib import Path
from dotenv import load_dotenv
import os

# ------------------------------------------------------------------
# Load .env from project root
# ------------------------------------------------------------------
BASE_DIR = Path(__file__).parent
# Adjust path if script isn't in project root
ENV_PATH = BASE_DIR / '.env'
if ENV_PATH.exists():
    load_dotenv(dotenv_path=ENV_PATH)
else:
    print(f"[Micro-C] WARN: .env not found at {ENV_PATH}")

# ------------------------------------------------------------------
# Configuration: Google Custom Search credentials
# ------------------------------------------------------------------
API_KEY   = os.getenv('GOOGLE_API_KEY')
SEARCH_CX = os.getenv('GOOGLE_CX')
if not API_KEY or not SEARCH_CX:
    print("[Micro-C] ERROR: Set GOOGLE_API_KEY and GOOGLE_CX in environment.")
    sys.exit(1)

# ------------------------------------------------------------------
# Paths & polling interval
# ------------------------------------------------------------------
BUS_DIR       = BASE_DIR / "bus"
IN_FILE       = BUS_DIR / "ai_in.txt"
OUT_FILE      = BUS_DIR / "ai_out.txt"
POLL_INTERVAL = 0.5  # seconds

# Ensure bus folder and files exist
BUS_DIR.mkdir(exist_ok=True)
IN_FILE.touch(exist_ok=True)
OUT_FILE.touch(exist_ok=True)


def pop_line(path: Path):
    with path.open("r+") as f:
        lines = f.readlines()
        if not lines:
            return None
        first, *rest = lines
        f.seek(0); f.truncate(); f.writelines(rest)
    return first.strip()

def push_line(path: Path, obj: dict):
    with path.open("a") as f:
        f.write(json.dumps(obj) + "\n")

def fetch_image_url(query: str) -> str:
    url = 'https://www.googleapis.com/customsearch/v1'
    params = {
        'key': API_KEY,
        'cx': SEARCH_CX,
        'q': query,
        'searchType': 'image',
        'num': 1
    }
    resp = requests.get(url, params=params, timeout=10)
    resp.raise_for_status()
    data = resp.json()
    items = data.get('items')
    if items and len(items) > 0:
        link = items[0].get('link', '')
        if link:
            return link
    raise ValueError('No image found in Google API response')

def handle_request(payload: dict):
    rid    = payload.get('recipe_id')
    prompt = (payload.get('prompt') or '').strip()
    if not prompt:
        push_line(OUT_FILE, {'recipe_id': rid, 'status': 'error', 'error': 'Empty prompt'})
        return
    try:
        image_url = fetch_image_url(prompt)
        push_line(OUT_FILE, {'recipe_id': rid, 'status': 'ok', 'image_url': image_url})
    except Exception as e:
        push_line(OUT_FILE, {'recipe_id': rid, 'status': 'error', 'error': str(e)})


def run_service():
    print('Google API image service listening… Ctrl-C to exit.')
    try:
        while True:
            line = pop_line(IN_FILE)
            if line:
                try:
                    req = json.loads(line)
                    handle_request(req)
                except json.JSONDecodeError:
                    push_line(OUT_FILE, {'status':'error','error':'bad JSON','raw': line})
            time.sleep(POLL_INTERVAL)
    except KeyboardInterrupt:
        print('\nShutting Down.')
        sys.exit(0)

if __name__ == '__main__':
    run_service()
