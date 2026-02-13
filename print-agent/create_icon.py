"""Run this once to generate icon.ico before building."""
from PIL import Image, ImageDraw

sizes = [16, 32,48, 64, 128, 256]
images = []

for size in sizes:
    img = Image.new("RGBA", (size, size), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)

    # Background
    draw.rounded_rectangle([0, 0, size-1, size-1], radius=size//6,
                            fill=(52, 58, 64))

    # Printer body
    pad = size // 6
    body_top = size // 3
    draw.rectangle(
        [pad, body_top, size-pad, size - pad - size//6],
        fill=(200, 200, 200)
    )
    # Paper coming out
    paper_w = size // 3
    paper_x = (size - paper_w) // 2
    draw.rectangle(
        [paper_x, size - pad - size//4, paper_x + paper_w, size - pad],
        fill=(255, 255, 255)
    )
    # Orange dot (status light)
    dot = size // 8
    draw.ellipse(
        [size - pad - dot - dot//2, body_top + dot//2,
         size - pad - dot//2, body_top + dot + dot//2],
        fill=(255, 140, 0)
    )
    images.append(img)

images[0].save("icon.ico", format="ICO", sizes=[(s, s) for s in sizes], append_images=images[1:])
print("icon.ico created successfully.")
