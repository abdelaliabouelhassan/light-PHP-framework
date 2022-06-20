

<?php

use App\core\DataBase;

class migration_0004_add_last_name_to_user_table extends DataBase
{

  
    public function up(){
        //add last name to user table
        $this->addColumn('users', 'last_name', 'varchar(255) NOT NULL');
    }
    

    public function down(){
        //remove last name from user table
        $this->dropColumn('users', 'last_name');
    }

    


   

}

?>