<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class AuthSeeder extends Seeder
{
	public function run()
	{
		$data = [
			[
				'fullname'       => 'Camilo Ramirez',
				'email'          => 'caramirez@ingeniopichichi.com',
				'username'       => 'caramirez',
				'password'       => password_hash('123456', PASSWORD_BCRYPT),
				'reset_token'    => '',
				'role'         	 => 1,
				'is_active'    	 => 1,
				'created_at' 	 => Time::now(),
				'updated_at'   	 => Time::now(),
				'deactivate_at' => null,
			]
		];
		$this->db->table('auth_users')->insertBatch($data);

		$data = [
			     [ 
				  'name'         => 'ROL_ADMIN', 
		          'display_name' => 'Administrador del Sistema',
				  'description'  => 'Usuario con los privilegios mas altos dentro de la aplicación',
				  'created_at'   => Time::now(),
				  'updated_at'   => Time::now(),  
				  ]
				];
		$this->db->table('auth_roles')->insertBatch($data);

//id	parent_id	order	title	icon	uri	routes	created_at	updated_at	

		$data = [
			[
			  'parent_id' 	=> '0', 
			  'order' 		=> '1',
			  'title'  		=> 'Configuración',
			  'icon'  		=> 'fa-cogs',
			  'uri'        	=> 'url:',
			  'routes'  	=> 'url:',
			  'created_at' 	=> Time::now(),
			  'updated_at' 	=> Time::now(),  
			],
			[
				'parent_id'  => '1', 
				'order'      => '1',
				'title'      => 'Usuarios',
				'icon'       => 'fa-users',
				'uri'        => 'url:',
				'routes'     => '/user-list',
				'created_at' => Time::now(),
				'updated_at' => Time::now(),  
			],
			[
				'parent_id'  => '1', 
				'order'      => '2',
				'title'      => 'Roles',
				'icon'       => 'fa-indent', //'fa-lock',
				'uri'        => 'url:',
				'routes'     => '/role-list',
				'created_at' => Time::now(),
				'updated_at' => Time::now(),  
			]
		];
	$this->db->table('auth_menus')->insertBatch($data);

	$data = [
		[
		  'role_id' => '1', 
		  'menu_id' => '1',
		  ]
		];
    $this->db->table('auth_roles_menus')->insertBatch($data);

	$data = [
		[
		  'user_id' => '1', 
		  'role_id' => '1',
		  ]
		];
    $this->db->table('auth_roles_users')->insertBatch($data);

	}
}
