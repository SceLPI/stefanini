<?php

use App\Enums\ProjectStatusEnum;
use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_status', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('code');
            $table->text('description');
            $table->boolean('should_justify');

            $table->timestamps();
        });

        DB::table('project_status')->insert([
            [
                'id' => ProjectStatusEnum::NEW ,
                'name' => 'New',
                'code' => 'new',
                'should_justify' => false,
                'description' => 'A new project waiting for development.',
            ],
            [
                'id' => ProjectStatusEnum::STARTED,
                'name' => 'Started',
                'code' => 'started',
                'should_justify' => false,
                'description' => 'Project started by development team.',
            ],
            [
                'id' => ProjectStatusEnum::WAITING_REVIEW,
                'name' => 'Waiting Review',
                'code' => 'waiting-review',
                'should_justify' => false,
                'description' => 'Q&A started review of this project.',
            ],
            [
                'id' => ProjectStatusEnum::REVIEWED,
                'name' => 'Reviewed',
                'code' => 'reviewed',
                'should_justify' => false,
                'description' => 'Project was reviewed by Q&A.',
            ],
            [
                'id' => ProjectStatusEnum::DEPLOYED,
                'name' => 'Deployed',
                'code' => 'deployed',
                'should_justify' => false,
                'description' => 'Project is deployed, but still not running for production purposes.',
            ],
            [
                'id' => ProjectStatusEnum::FINISHED,
                'name' => 'Finished',
                'code' => 'finished',
                'should_justify' => false,
                'description' => 'Project is finished and running for production purposes.',
            ],
            [
                'id' => ProjectStatusEnum::CANCELED,
                'name' => 'Canceled',
                'code' => 'canceld',
                'should_justify' => true,
                'description' => 'The project is cancelled. Should be justified.',
            ],
            [
                'id' => ProjectStatusEnum::ON_HOLD,
                'name' => 'On Hold',
                'code' => 'on-hold',
                'should_justify' => true,
                'description' => 'The project is on hold for any reason. Should be justified.',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_status');
    }
};


