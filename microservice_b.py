import json, time, sys
from pathlib import Path
BUS = Path(__file__).resolve().parents[0] / "bus"
IN  = BUS / "sort_in.txt"
OUT = BUS / "sort_out.txt"
IN.touch(exist_ok=True); OUT.touch(exist_ok=True)

def pop():
    with IN.open("r+") as f:
        lines = f.readlines()
        if not lines: return None
        first,*rest = lines
        f.seek(0); f.truncate(); f.writelines(rest)
    return first.strip()

def push(obj):
    with OUT.open("a") as f:          # "a" = append
        f.write(json.dumps(obj) + "\n")

def handle(req):
    recipes = req.get('recipes', [])
    key     = req.get('by',  'title')
    rev     = req.get('dir', 'asc') == 'desc'

    try:
        sorted_recipes = sorted(recipes, key=lambda r: r[key], reverse=rev)
    except KeyError:
        sorted_recipes = recipes     # fallback if bad key

    push({
        "request_id": req["request_id"],
        "recipes": sorted_recipes
    })

def run():
    print("[SortSvc] listening…")
    while True:
        line = pop()
        if line:
            try: handle(json.loads(line))
            except json.JSONDecodeError: pass
        time.sleep(0.2)

if __name__ == "__main__": run()
