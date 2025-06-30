import sys
import json
from yt_dlp import YoutubeDL

def get_video_info(url):
    ydl_opts = {
        'format': 'bestaudio/best',
        'postprocessors': [{
            'key': 'FFmpegExtractAudio',
            'preferredcodec': 'mp3',
            'preferredquality': '192',
        }],
    }

    try:
        with YoutubeDL(ydl_opts) as ydl:
            info = ydl.extract_info(url, download=False)
            
            formats = []
            for f in info['formats']:
                if f.get('format_id') and f.get('ext') and f.get('format_note'):
                    formats.append({
                        'format_id': f['format_id'],
                        'ext': f['ext'],
                        'format_note': f['format_note']
                    })

            return {
                'title': info['title'],
                'thumbnail': info['thumbnail'],
                'formats': formats
            }
    except Exception as e:
        return {'error': str(e)}

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print(json.dumps({'error': 'Invalid arguments'}))
    else:
        result = get_video_info(sys.argv[1])
        print(json.dumps(result))

