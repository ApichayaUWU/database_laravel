lab8 ทำตามchat gptน่ะ __
......หลังจาก seeding ตาราง personality type ได้ ........
- เราสร้่างไฟล์ migration อีกอันเอามาเพิ่ม attribute ใส่ตาราง users (สุดแล้วแต่จะตั้งชื่อเลย เราตั้งชื่อว่า personality_type_id)
  ```
   ./vendor/bin/sail artisan make:migration add_personality_type_id_to_users_table
  ```
