<?php

use App\core\DataBase;

class migration_0001_user_table extends DataBase
{

  
    

    public function up()
    {
        $this->createTable('users', [
            'id' => 'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'name' => 'varchar(255) NOT NULL',
            'email' => 'varchar(255) NOT NULL',
            'password' => 'varchar(255) NOT NULL',
            'created_at' => 'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ]);
    }

    public function down(){
        $this->dropTable('users');
    }


   

}

?>