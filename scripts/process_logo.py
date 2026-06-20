from collections import deque
from pathlib import Path

from PIL import Image

src = Path(r"C:\Users\KILROY\.cursor\projects\b-Documents-GitHub-RetrieveIT-Lost-and-Found\assets\retrieveit-logo.png")
img = Image.open(src).convert("RGBA")
pixels = img.load()
width, height = img.size


def is_background(r, g, b, a):
    if a == 0:
        return True
    # Remove black and near-white edge backgrounds.
    if max(r, g, b) < 55:
        return True
    return r > 240 and g > 240 and b > 240


visited = [[False] * width for _ in range(height)]
queue = deque()

for x in range(width):
    queue.append((x, 0))
    queue.append((x, height - 1))
for y in range(height):
    queue.append((0, y))
    queue.append((width - 1, y))

while queue:
    x, y = queue.popleft()
    if x < 0 or y < 0 or x >= width or y >= height or visited[y][x]:
        continue

    r, g, b, a = pixels[x, y]
    if not is_background(r, g, b, a):
        continue

    visited[y][x] = True
    pixels[x, y] = (0, 0, 0, 0)
    queue.extend([(x - 1, y), (x + 1, y), (x, y - 1), (x, y + 1)])

bbox = img.getbbox()
if bbox:
    img = img.crop(bbox)

out = Path(r"B:\Documents\GitHub\RetrieveIT-Lost-and-Found\public\images\logo.png")
img.save(out, "PNG")
print(f"saved {out.name} {img.size[0]}x{img.size[1]}")
