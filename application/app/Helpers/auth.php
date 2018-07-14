<?php

function isSuperAdmin()
{
	if(!Auth::check()){
		return false;
	}

	return Auth::user()->role->name == 'Super Admin' ? true : false;
}

function isAdmin()
{
	if(!Auth::check()){
		return false;
	}

	return Auth::user()->role->name == 'Admin' ? true : false;
}

function isEmployee()
{
	if(!Auth::check()){
		return false;
	}

	return Auth::user()->role == 'employee' ? true : false;
}

function isDepartmentAdmin()
{
	if(!Auth::check()){
		return false;
	}

	return Auth::user()->role == 'department-admin' ? true : false;
}

function isSuperAdminOrAdmin()
{
	if(!Auth::check()){
		return false;
	}

	return in_array(Auth::user()->role, ['super-admin', 'admin']) ? true : false;
}

function isSuperAdminOrAdminOrDepartmentAdmin()
{
	if(!Auth::check()){
		return false;
	}

	return in_array(Auth::user()->role, ['super-admin', 'admin', 'department-admin']) ? true : false;
}

function isDepartmentAdminOrEmployee()
{
	if(!Auth::check()){
		return false;
	}

	return in_array(Auth::user()->role, ['department-admin', 'employee']) ? true : false;
}