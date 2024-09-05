lab8 ทำตามchat gptน่ะ <br/>
- หลังจาก seeding ตาราง personality type ได้แล้ว
- เราสร้างไฟล์ migration อีกอันเอามาเพิ่ม attribute ใส่ตาราง users (สุดแล้วแต่จะตั้งชื่อเลย เราตั้งชื่อว่า personality_type_id)
  ```
   ./vendor/bin/sail artisan make:migration add_personality_type_id_to_users_table
  ```
- เขียนไฟล์ migrate ของ add ให้เรียบร้อย(คุ้ยเอาในgitนี้ก็ได้) แล้วก็ ./vendor/bin/sail artisan migrate
- ลองไปใส่ค่าให้ personality_type_id ในdbeaver เผื่อไว้test (เราไม่ได้ทำmethod update ยังไม่ได้ลอง ไม่รู้ทำไง)
- ไปที่ไฟล์ Models/PersonalityType.php ที่สร้างไว้ ยัดโค้ด
  ```
  public function user()
    {
        return $this->hasMany(User::class, 'personality_type_id');
    }
  ```
- ไปที่ไฟล์ Models/User.php ที่สร้างไว้ ยัดโค้ด
  ```
  public function personalityType()
    {
        return $this->belongsTo(PersonalityType::class, 'personality_type_id');
    }
  ```
- ไปที่ UserController.php กำหนด view ให้มัน(chat gpt บอกมา)
    ```
    public function showPersonality($id)
    {
        $user = User::with('personalityType')->find($id); // Eager load personalityType
        return view('user.profile', compact('user'));
    }
    ```
- ไปเขียน frontend ที่ resource > views > profile > partial > update-profile-information-form.blade บรรทัดประมาณ55-56 ระหว่าง bio กับ save <br/>
  ส่วนโค้ดfrontendนี่แล้วแต่เลย เขียนไงก็ได้ ของเราเขียนงี้ ตลกนิดนึง แต่ไม่แก้ละ เหนื่อย
  ```
   <!-- ////////////////////////// -->
        <!-- edit personality view here -->
        <h4 class="font-medium text-blue-900 dark:text-blue-100">
            {{ __('Personality Type Information ') }}
        </h4>
        <p class="mt-2 text-blue-900 dark:text-blue-100">
            {{$user->personalityType->type  ?? 'No personality available' }}
            , {{$user->personalityType->description ?? '' }}
        </p>
        <!-- ////////////////////////// -->
  ```
