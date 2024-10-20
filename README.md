# user_registrator_test_task
Just test_task


Endpoint: http://3.73.86.8:3333/
Content-Type: multipart/form-data;


markdown table:
| ID  | Description                                           | Scenario                                                                                       | TestData                                                                                                                                                                              | Expected                                                                                                                                                                                                                                          | is_automated | Status  | Artifact                                                                                                                                                                     |
|-----|-------------------------------------------------------|-----------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|--------------|---------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| 0   | Проверка доступности эндпоинта                       | Посылаем Get запрос, смотри ответ                                                          | `curl --location 'http://3.73.86.8:3333/'`                                                                                                                                          | `200 {"greeting":"Welcome to the Adonis API tutorial"}`                                                                                                                                                                                   | Yes          | success |                                                                                                                                                                              |
| 1   | Попытка успешной регистрации пользователя             | Создаём нового пользователя с уникальными валидными данными post запросом FormData         | `curl --location 'http://3.73.86.8:3333/user/create' --form 'username="Yt_test_name_2"' --form 'email="yt_e.mail1@gmail.ru"' --form 'password="qwepoi123"'`                    | `200 {"success":true,"details":{"username":"Yt_test_name_2","email":"yt_e.mail1@gmail.ru","password":"$2a$10$1v/ohpw1iZR7WXzWAULyGuAyhwyH2WgI9wlUEkRNW8h6msvEqypHG","created_at":"2024-10-16 20:42:20","updated_at":"2024-10-16 20:42:20","id":8},"message":"User Successully created"}` | Yes          | success | Минорная проблема. Слово 'Successully' в ответе должно писаться как 'Successfully'.                                                                                          |
| 2   | Проверка создания пользователя в системе после регистрации | Используем кейс ID:1 для регистрации. Запоминаем ID пользователя и далее делаем гет запрос с параметром ID, проверяем, что details из регистрации совпадают с запрошенным | `curl --location 'http://3.73.86.8:3333/get?id=8'`                                                                                                                                 | `[{ "id": 8, "username": "test_user_16d64f5d-32e2-4335-a42e-9902e57fa9ef", "email": "aeec2209-f0a3-43fb-9563-8f0c00823c90@gmail.com", "password": "$2a$10$aTHoctxm77HEs/Av38iTaeqJU1GhXWWInNxkqAIqTlTDRzgLJe3ia", "created_at": "2024-10-17 05:29:43", "updated_at": "2024-10-17 05:29:43" }]` | Yes          | success |                                                                                                                                                                              |
| 3   | Проверка доступности всех пользователей               | Делаем гет запрос, проверяем, что отдаётся список всех пользователей                       | `curl --location 'http://3.73.86.8:3333/get'`                                                                                                                                       | `[{ "id": int, "username": "str", "email": "str", "password": "str", "created_at": "date", "updated_at": "date" }, { "id": 2, "username": "test_user_16d64f5d-32e2-4335-a42e-9902e57fa9ef", "email": "aeec2209-f0a3-43fb-9563-8f0c00823c90@gmail.com", "password": "$2a$10$aTHoctxm77HEs/Av38iTaeqJU1GhXWWInNxkqAIqTlTDRzgLJe3ia", "created_at": "2024-10-17 05:29:43", "updated_at": "2024-10-17 05:29:43" }]` | Yes          | success |                                                                                                                                                                              |
| 4   | Валидация для username                                | Проверяем 1) Пустое username 2) Существующий username 3) Правила валидации для юзернейм (длинна и спецсимволы) 4) Проверка на нецензурные слова | 1) `{username: ''}` , 2) `{username: 'test_user'}` , 3) `{username: '_'}`, 4) `{username: 'test_fuck'}`                                                                          | 1) `400 {"success": false,"message": ["A username is required"]}` 2) `400 {"success": false,"message": ["This username is taken. Try another"]}` 3) `400 {"success": false,"message": ["This username non-compliant with rules. Try another"]}` 4) `400 {"success": false,"message": ["This username non-compliant with rules. Try another"]}` | No           | Fail    | Объединил кейсы, чтобы в случае падения было ясно, что перестала работать правильно валидация по юзернейму.                                                                  |
| 5   | Валидация для email                                  | Проверяем 1) Пустой email 2) Существующий email 3) Правила валидации для почт 3.1) наличие @ и существующего домена 3.2) почты с точками test.test@gogle.com 3.3) Киррилические почты ПетяВасильев@москва.рф 4) Проверка на нецензурные слова | 1) `{email: ''}` , 2) `{email: 'test_user@google.com'}` , 3.1) `{email: 'test_user.google.com'}`, 3.2) `{email: 'test.user@google.com'}` 3.3) `{email: 'ПетяВасильев@москва.рф'}` 4) `{email: 'test_fuck@gmail.com'}` | 1) `400 {"success": false,"message": ["An Email is required"]}` 2) `400 {"success": false,"message": ["Email already exists"]}` 3.1) `400 {"success": false,"message": ["Invalid Email"]}` 3.2) `200 {"success": true,"message": "details":{...}}` 3.3) `200 {"success": true,"message": "details":{...}}` 4) `400 {"success": false,"message": ["This email non-compliant with rules"]}` | No           | Fail    | Нет валидации почты по 3.1 и 4                                                                                                                                           |
| 6   | Валидация для password                               | Проверяем 1) Пустой password 2) Существующий password 3) Правила валидации для паролей на проекте 4) Проверяем, что не храни пароль в открытом виде гет запросом 5) Проверяем, что хеш валиден | 1) `{password: ''}` , 2) `{password: 'qwepoi12'}` , 3) `{password: '123'}`, 4) `{password: 'qwepoi123'}` 5) `...` | 1) `400 {"success": false,"message": ["A password for the user"]}` 2) `200 {"success": true,"message": "details":{...}}` 3) `400 {"success": false,"message": ["This password non-compliant with pattern"]}` 4) Пароль храниться в виде "$2a$10$aTHoctxm77HEs/Av38iTaeqJU1GhXWWInNxkqAIqTlTDRzgLJe3ia" 5) Сопоставить пароль и его Bcrypt hash decrypt | No           | Fail    | Допустимы слишком небезопасные пароли, нет паттернов для паролей (обязательно большая буква, спец символ и т.д.)                                                                       |
| 7   | Проверка типа данных Content-Type:form-data         | Проверить что другой тип данных (например json) не проходит                               | `curl --location 'http://3.73.86.8:3333/user/create' --header 'Content-Type: application/json' --data-raw '{"username": "test_name_5","email": "Rrty@gmail1.com","password": "123"}'` | `400 Invalid content type`                                                                                                                                                                                                                   | No           | Fail    | Json прекрасно работает, хотя кажется не должен                                                                                                                                          | 


plane text:

ID: 0
Description: Проверка доступности эндпоинта
Scenario: Посылаем Get запрос, смотри ответ
TestData: curl --location 'http://3.73.86.8:3333/
Expected: 200 {"greeting":"Welcome to the Adonis API tutorial"}
is_automated: Yes
Status: success

ID: 1
Description: Попытка успешной регистрации пользователя
Scenario: Создаём нового пользователя с уникальными валидными данными post запросом FornData
TestData: curl --location 'http://3.73.86.8:3333/user/create' --form 'username="Yt_test_name_2"' --form 'email="yt_e.mail1@gmail.ru"' --form 'password="qwepoi123"'
Expected: 200 {"success":true,"details":{"username":"Yt_test_name_2","email":"yt_e.mail1@gmail.ru","password":"$2a$10$1v/ohpw1iZR7WXzWAULyGuAyhwyH2WgI9wlUEkRNW8h6msvEqypHG","created_at":"2024-10-16 20:42:20","updated_at":"2024-10-16 20:42:20","id":8},"message":"User Successully created"}
is_automated: Yes
Status: success
Artifact: Минорная проблема. Слово 'Successully' в ответе должно писаться как 'Successfully'.

ID: 2
Description: Проверка создания пользователя в системе после регистрации
Scenario: Используем кейс ID:1 для регистрации. Запоминаем ID пользователя и далее делаем гет запроc с параметром ID, проверяем, что details из регистрации совпадают с запрошенным
TestData: curl --location 'http://3.73.86.8:3333/get?id=8'
Expected:
[
{
"id": 8,
"username": "test_user_16d64f5d-32e2-4335-a42e-9902e57fa9ef",
"email": "aeec2209-f0a3-43fb-9563-8f0c00823c90@gmail.com",
"password": "$2a$10$aTHoctxm77HEs/Av38iTaeqJU1GhXWWInNxkqAIqTlTDRzgLJe3ia",
"created_at": "2024-10-17 05:29:43",
"updated_at": "2024-10-17 05:29:43"
}
]
is_automated: Yes
Status: success


ID: 3
Description: Проверка доступности всех пользователей
Scenario: Делаем гет запрос, проверяем, что отдаётся список всех пользователей. Помним, что базовые 7 пользователей лежат в базе, остальные периодически очищаются.
TestData: curl --location 'http://3.73.86.8:3333/get
Expected: Json вида:
[
{
"id": int,
"username": "str",
"email": "str",
"password": "str",
"created_at": "date",
"updated_at": "date"
}
{
"id": 2,
"username": "test_user_16d64f5d-32e2-4335-a42e-9902e57fa9ef",
"email": "aeec2209-f0a3-43fb-9563-8f0c00823c90@gmail.com",
"password": "$2a$10$aTHoctxm77HEs/Av38iTaeqJU1GhXWWInNxkqAIqTlTDRzgLJe3ia",
"created_at": "2024-10-17 05:29:43",
"updated_at": "2024-10-17 05:29:43"
}
]
is_automated: yes
Status: success


Проверки полей для /create

ID: 4
Description: Валидация для username
Scenario: Проверяем 1) Пустое username 2) Существующий username 3) Правила валидации для юзернейм (длинна и спецсимволы) 4) Проверка на нецензурные слова
TestData: 1) {username: ''} , 2) {username: 'test_user'} , 3) {username: '_'}, 4) {username: 'test_fuck'}
Expected:
1) 400 {"success": false,"message": ["A username is required"]}
2) 400 {"success": false,"message": ["This username is taken. Try another"]}
3) 400 {"success": false,"message": ["This username non-compliant with rules. Try another"]}
4) 400 {"success": false,"message": ["This username non-compliant with rules. Try another"]}
is_automated: NO
Status: Fail
Artifact:
Примечание: Объединил кейсы, чтобы в случае падения было ясно, что перестала работать правильно валидация по юзернейму, в подробном отчёте можно вывести пункт из разряда "Валадиация юзернейм => мат. фильтры"

ID: 5
Description: Валидация для email
Scenario:
Проверяем
1) Пустой email
2) Существующий email
3) Правила валидации для почт
3.1) наличие @ и существующего домена
3.2) почты с точками test.test@gogle.com
3.3) Киррилические почты ПетяВасильев@москва.рф
4) Проверка на нецензурные слова
TestData: 1) {email: ''} , 2) {email: 'test_user@google.com'} , 3.1) {email: 'test_user.google.com'}, 3.2) {email: 'test.user@google.com'} 3.3) {email: 'ПетяВасильев@москва.рф'} 4) {email: 'test_fuck@gmail.com'}
Expected:
1) 400 {"success": false,"message": ["An Email is required"]}
2) 400 {"success": false,"message": ["Email already exists"]}
3.1) 400 {"success": false,"message": ["Invalid Email"]}
3.2) 200 {"success": true,"message": "details":{...}}
3.3) 200 {"success": true,"message": "details":{...}}  
4) 400 {"success": false,"message": ["This email non-compliant with rules"]}
is_automated: No
Status: Fail
Artifact: Нет валидации почты по 3.1 и 4

ID: 6
Description: Валидация для password
Scenario:
Проверяем
1) Пустой password
2) Существующий password (да да, это не копипаста, просто ожидаем, что нет сообщения в духе "такой пароль существует")
3) Правила валидации для паролей на проекте
4) Проверяем, что не храми пароль в открытом виде гет запросом
5) Проверяем, (зная пароль свежесозданного пользователя, что хеш валиден) скорее всего это Bcrypt hash
TestData: 1) {password: ''} , 2) {password: 'qwepoi12'} , 3) {password: '123'},  4) {password: 'qwepoi123'} 5) ...
Expected:
1) 400 {"success": false,"message": ["A password for the user"]}
2) 200 {"success": true,"message": "details":{...}}
3) 400 {"success": false,"message": ["This password non-compliant with patern"]}
4) Пароль храниться в виде "$2a$10$aTHoctxm77HEs/Av38iTaeqJU1GhXWWInNxkqAIqTlTDRzgLJe3ia"
5) Сопоставить пароль и его Bcrypt hash decrypt   
is_automated: No
Status: Fail
Artifact: допустимы слишком небезопасные пароли в т.ч в один символ, нет паттернов для паролей (обязательно большая буква, спец символ и тд)


ID: 7
Description: Проверка типа данных Content-Type:form-data
Scenario: Проверить что другой тип данный (например json) не проходит (спорный кейс, но заявлена работа только с форм дата)
TestData:
    curl --location 'http://3.73.86.8:3333/user/create' \
    --header 'Content-Type: application/json' \
    --data-raw '{
    "username": "test_name_5",
    "email": "Rrty@gmail1.com",
    "password": "123"
    }'
Expected: 400 Invalid content type
is_automated: No
Status: Fail
Artifact: Json прекрасно работает, хотя кажется не должен
