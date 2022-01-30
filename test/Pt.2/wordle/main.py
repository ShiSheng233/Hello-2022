#! /usr/bin/python3
# -*- coding: utf-8 -*-

import os
import random
import datetime

from threading import Timer
from flask import Flask, request, abort, g, jsonify, session, render_template, redirect

app = Flask(__name__)

# 一个简单的，能被 https://github.com/Paradoxis/Flask-Unsign 爆破的key
app.secret_key = '123456'
app.permanent_session_lifetime = datetime.timedelta(days=30)

# the flag
flag = 'flag{th3_b1tc0in_1s_a_lie}'

word_id = -1
words_list = list()
next_word_time = 0

def init_words_list():
    global words_list
    f = open('words.txt', 'r')
    for w in f.readlines():
        words_list.append(w.strip().upper())
    f.close()
    random.shuffle(words_list)

def get_new_word():
    global word_id, words_list
    word_id += 1
    word_id %= len(words_list)
    global t, next_word_time
    # update after 10 minutes
    next_word_time = datetime.datetime.now() + datetime.timedelta(minutes=10)
    t = Timer(10*60, get_new_word)
    t.start()

@app.before_request
def make_session_permanent():
    session.permanent = True

# 取得猜测结果
def guess_word(word):
    char_cnt = {}
    res = [' '] * 5
    for i in range(5):
        char_cnt[words_list[word_id][i]] = char_cnt.get(words_list[word_id][i], 0) + 1

    # 跑两次是因为要先减去正确的字母的 cnt
    for i in range(5):
        if word[i] == words_list[word_id][i]:
            res[i] = "right"
            char_cnt[words_list[word_id][i]] -= 1
    for i in range(5):
        if word[i] == words_list[word_id][i]:
            pass
        elif char_cnt.get(word[i], 0) > 0:
            res[i] = "present"
            char_cnt[word[i]] -= 1
        else:
            res[i] = "absent"
    return res

@app.route('/', methods=['GET'])
def query():
    global flag, word_id
    # new user
    if 'reg' not in session:
        session['reg'] = datetime.datetime.now().timestamp()
        session['level'] = 0
    
    # word was updated
    session_word_id = session.get('word_id', -1)
    if session_word_id != word_id:
        session['word_id'] = word_id
        session['tries'] = list()
        session['guessed'] = False
    
    is_guessed = "true" if session.get('guessed', False) else "false"
    is_gameover = session.get('game_over', False)
    guess_cnt = len(session.get('tries'))
    msg = "QAQ Server Error"

    guess_list = [[[' ', ' '] for j in range(5)] for i in range(6)]
    for i in range(len(session.get('tries'))):
        word = session['tries'][i]
        res = guess_word(word)
        for j in range(5):
            guess_list[i][j][0] = word[j]
            guess_list[i][j][1] = res[j]

    if session.get('level', 0) >= 300:
        msg = flag
    elif is_gameover:
        msg = 'Game Over! click <a href="/retry">here</a> to restart!'
    else:
        msg = 'Level: ' + str(session.get('level', 0))

    return render_template('index.html', 
                            next_word = next_word_time.strftime("%Y-%m-%d %H:%M:%S"),
                            guess_cnt = guess_cnt, 
                            is_guessed = is_guessed, 
                            msg = msg, 
                            guess_rows = guess_list)

@app.route('/retry', methods=['GET'])
def retry():
    session['level'] = 0
    session['tries'] = list()
    session['guessed'] = False
    session['game_over'] = False
    return redirect('/')

# return:
# {
#   "result": -1,0,1,2,3
#   "word": word
#   "char_stat": ["right", "present", "present", "right", "absent"]
# }
@app.route('/guess/<word>', methods=['GET'])
def guess(word):
    global words_list, word_id
    session_word_id = session.get('word_id')
    result_map = {}

    result_map['word'] = word

    if session_word_id != word_id:
        result_map['result'] = 3
    elif word not in words_list:
        result_map['result'] = 2
    else:
        if (len(session.get('tries')) >= 6 or session.get('game_over')):
            result_map['result'] = -1 # game over
        else:
            result_map['char_stat'] = guess_word(word)

            if 'tries' not in session:
                session['tries'] = list()
            session['tries'].append(word)

            if word == words_list[word_id]:
                session['level'] = session.get('level', 0) + 1
                session['guessed'] = True
                result_map['result'] = 1
            else:
                if len(session['tries']) >= 6:
                    session['game_over'] = True
                result_map['result'] = 0

    return jsonify(result_map)

init_words_list()
get_new_word()

if __name__ == '__main__':
    app.run(debug=False, port=2333)       
