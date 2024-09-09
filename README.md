# lab8 ทำตามchat gptน่ะ <br/>

## ทำ Migrates
- หลังจาก seeding ตาราง personality type ได้แล้ว (ชื่อตารางที่เราใช้ในแลปนี้คือ personality_types)
- เราสร้างไฟล์ migration อีกอันเอามาเพิ่ม attribute ใส่ตาราง users(ถ้าถามว่าทำไม มันดูง่ายกว่าทางอื่นมั้ง) (สุดแล้วแต่จะตั้งชื่อเลย เราตั้งชื่อว่า personality_type_id)
  ```
   ./vendor/bin/sail artisan make:migration add_personality_type_id_to_users_table
  ```
- เขียนไฟล์ migrate ของ add_personality_type_id_to_users_table.php ให้เรียบร้อย(คุ้ยเอาในgitนี้ก็ได้) แล้วก็ค่อย ./vendor/bin/sail artisan migrate <br/> ไฟล์ migration (กรณีที่migrateไปแล้วใช้ไม่ได้ ตัวdatabaseมันอาจจะดีเลย์ บางทีก็นานเป็นวัน อาจจะลองรีสตาร์ทคอม/โปรแกรมดู)
  ```
  <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::table('users', function (Blueprint $table) {
                // Add foreign key to the personality_types table
                $table->unsignedBigInteger('personality_type_id')->nullable();
                $table->foreign('personality_type_id')
                      ->references('id')
                      ->on('personality_types')
                      ->onDelete('set null'); // Use 'set null' to allow users to have no personality type if it's deleted
            });
        }

        public function down(): void
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['personality_type_id']);
                $table->dropColumn('personality_type_id');
            });
        }
    };

  ```
- ค่า personality_type_id ในตาราง users จะnull ถ้าจะtestก็ไปmanual insertในdbeaverเผื่อไว้ หรือจะเขียนmethodให้laravelเพิ่มเองก็ได้ แต่เราไม่ทำละ เขาไม่ได้บอก
## ทำ Models
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
## ทำ Views
- ไปที่ UserController.php กำหนด view ให้มัน(chat gpt บอกมา)
  ```
  use App\Models\PersonalityType;
  ```
    ```
    public function showPersonality($id)
    {
        $user = User::with('personalityType')->find($id); 
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
