# lab8 ref.chat gpt หมายเหตุ ผู้เขียนใช้dark mode ใครใช้light mode ไปแก้frontendเองเด้อ <br/>

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
- <mark>ค่า personality_type_id ในตาราง users จะnull ถ้าจะtestก็ไปmanual insertในdbeaverเผื่อไว้ หรือจะเขียนmethodให้laravelเพิ่มเองก็ได้ แต่เราไม่ทำละ เขาไม่ได้บอก(ใครทำได้ขอหลอกหน่อยยยย)</mark>
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
## (optional) update personality(ได้ไง) *** เช็คชื่อตัวแปร attribute ตารางกับชื่อmodelที่ตัวเองใช้ด้วย
- app > Http > Controllers > Requests > ProfileUpdateRequest.php เติมอันนี้
  ```
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
  
               // added here
            'personality_type_id' => 'required|exists:personality_types,id',
        ];
  ```
- ที่ file ProfileController.php เติมอันนี้ แล้วก็อย่าลืม use App\Models\PersonalityType;
  ```
  use App\Models\PersonalityType;

  public function edit(Request $request): View
    {
        // edited
        $user = auth()->user();
        $personalityTypes = PersonalityType::all(); 
        return view('profile.edit', compact('user', 'personalityTypes'));
    
        // old version
        // return view('profile.edit', [
        //     'user' => $request->user(), 
        // ]);
    }
  ```
- เพิ่มcode frontend ที่หน้าเดิม เอาไปวางต่อจาก show personality ก็ได้
  ```
            <!-- The dropdown for changing personality type  -->
            <div class="mt-2 flex gap-4 max-h-[40px] align-middle">
                <label for="personality_type_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Change MBTI') }}</label>
                <select id="personality_type_id" name="personality_type_id" class="block mt-1 w-full rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
                    <option value="">-- Select Personality --</option>
                        @foreach($personalityTypes as $personalityType)
                        <option value="{{ $personalityType->id }}" {{ $user->personality_type_id == $personalityType->id ? 'selected' : '' }}>
                            {{ $personalityType->type }} 
                        </option>
                        @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('personality_type_id')" />
            </div>    
  ```
