# microservice_d_cooking_tip.py
"""
Microservice D – Random Cooking‑Tip Generator (file‑bus edition)
----------------------------------------------------------------
• Watches   bus/tips_in.txt   for JSON requests:
• Responds with a random tip in   bus/tips_out.txt :

Run:   python3 microservice_d_cooking_tip.py
Stop:  Ctrl‑C
"""
import json, random, time, sys
from pathlib import Path

# ------------------------------------------------------------------
# Paths & polling interval
# ------------------------------------------------------------------
BUS_DIR        = Path(__file__).parent / "bus"
POLL_INTERVAL  = 0.5          # seconds
IN_FILE        = BUS_DIR / "tips_in.txt"
OUT_FILE       = BUS_DIR / "tips_out.txt"

BUS_DIR.mkdir(exist_ok=True)
IN_FILE.touch(exist_ok=True)
OUT_FILE.touch(exist_ok=True)

# ------------------------------------------------------------------
# Simple line‑queue helpers
# ------------------------------------------------------------------

def pop_line(path: Path):
    """Remove & return first line; return None if file empty"""
    with path.open("r+") as f:
        content = f.readlines()
        if not content:
            return None
        first, *rest = content
        f.seek(0); f.truncate(); f.writelines(rest)
    return first.strip()

def push_line(path: Path, obj: dict):
    with path.open("a") as f:
        f.write(json.dumps(obj) + "\n")


TIPS = [
    "Always preheat your pan before adding oil to prevent sticking.",
    "Let meat rest after cooking to retain juices.",
    "Add a pinch of salt to sweet dishes to enhance flavor.",
    "Deglaze pans with wine or stock for instant sauce.",
    "Use a sharp knife—it's safer and more precise.",
    "Taste as you cook and adjust seasoning gradually.",
    "Store herbs like flowers: stems in water, loosely covered.",
    "Reserve pasta water; the starch helps sauces cling.",
    "Toast nuts and spices to unlock deeper flavors.",
    "Clean as you go to keep your workspace organized.",
]

def run():
    print("Cooking‑tip service listening… Ctrl‑C to exit.")
    try:
        while True:
            line = pop_line(IN_FILE)
            if line:
                try:
                    payload = json.loads(line)
                    req_id  = payload.get("request_id")
                    tip     = random.choice(TIPS)
                    push_line(OUT_FILE, {"request_id": req_id, "tip": tip})
                except json.JSONDecodeError:
                    push_line(OUT_FILE, {"status":"error","error":"bad JSON","raw":line})
            time.sleep(POLL_INTERVAL)
    except KeyboardInterrupt:
        print("\nShutting Down.")
        sys.exit(0)

if __name__ == "__main__":
    run()
