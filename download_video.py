import sys
import os
from yt_dlp import YoutubeDL

def download_video(url, format_id):
    output_template = 'downloads/%(title)s-%(format_id)s.%(ext)s'
    ydl_opts = {
        'format': format_id,
        'outtmpl': output_template,
    }

    try:
        with YoutubeDL(ydl_opts) as ydl:
            info = ydl.extract_info(url, download=True)
            filename = ydl.prepare_filename(info)
            return filename
    except Exception as e:
        print(f"Error: {str(e)}", file=sys.stderr)
        sys.exit(1)

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python download_video.py <url> <format_id>", file=sys.stderr)
        sys.exit(1)

    url = sys.argv[1]
    format_id = sys.argv[2]
    
    filename = download_video(url, format_id)
    print(filename)

