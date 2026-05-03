public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();

        $table->string('userId')->unique(); // email as userId

        $table->string('password');

        $table->timestamps(); // created_at, updated_at

        $table->timestamp('last_login')->nullable();
    });

    // optional (keep as it is)
    Schema::create('password_reset_tokens', function (Blueprint $table) {
        $table->string('email')->primary();
        $table->string('token');
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('sessions', function (Blueprint $table) {
        $table->string('id')->primary();
        $table->foreignId('user_id')->nullable()->index();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
    });
}