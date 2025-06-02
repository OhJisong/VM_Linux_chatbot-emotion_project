from flask import Flask, request, jsonify
import random

app = Flask(__name__)

@app.route('/chat', methods=['POST'])
def chat():
    user_text = request.json.get('text', '')

    if "피곤" in user_text or "지쳤" in user_text:
        emotion = "피곤"
        replies = [
            "요즘 많이 지치셨군요. 잠깐 쉬는 것도 괜찮아요.",
            "많이 피곤하신 것 같아요. 잠깐 눈을 붙여보는 건 어때요?",
            "오늘 하루 너무 고생했어요. 토닥토닥."
        ]
    elif "슬퍼" in user_text or "눈물" in user_text or "울" in user_text:
        emotion = "슬픔"
        replies = [
            "마음이 많이 아팠겠어요. 얘기해줘서 고마워요.",
            "슬픈 일이 있었군요. 곁에 있어줄게요.",
            "울고 싶을 땐 울어도 돼요. 함께할게요."
        ]
    elif "화나" in user_text or "짜증" in user_text or "미친놈" in user_text:
        emotion = "화남"
        replies = [
            "그런 상황이면 정말 화가 날 것 같아요!",
            "어휴, 너무 속상했겠어요!",
            "화날 만해요. 내가 들어줄게요."
        ]
    else:
        emotion = "기쁨"
        replies = [
            "좋은 기분이네요! 무슨 일 있었어요?",
            "오늘 뭔가 즐거운 일이 있었나봐요?",
            "기분 좋아 보여서 나도 기분이 좋아졌어요!"
        ]

    reply = random.choice(replies)
    return jsonify({"emotion": emotion, "reply": reply})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)

