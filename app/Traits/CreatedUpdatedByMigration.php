<?php

namespace App\Traits;

trait CreatedUpdatedByMigration
{

    use DatabaseNames;
    public function createdUpdatedByRelationship($table)
    {
        $table->unsignedBigInteger('created_by')->nullable()->index();
        $table->foreign('created_by')->references('id')->on($this->getMainDatabaseName() .'.users')->onUpdate('cascade');

        $table->unsignedBigInteger('updated_by')->nullable()->index();
        $table->foreign('updated_by')->references('id')->on(''.$this->getMainDatabaseName().'.users')->onUpdate('cascade');
    }
}
