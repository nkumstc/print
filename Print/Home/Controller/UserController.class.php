<?php
// ===================================================================
// | FileName: 		UserController.class.php
// ===================================================================
// | Discription：	UserController 用户控制器
//		<命名规范：>
// ===================================================================
// +------------------------------------------------------------------
// | 云印南开
// +------------------------------------------------------------------
// | Copyright (c) 2014 云印南开团队 All rights reserved.
// +------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +------------------------------------------------------------------
/**
* Class and Function List:
* Function list:
* - index()
* Classes list:
* - UserController extends Controller
*/
namespace Home\Controller;
use Think\Controller;
import('Common.Urp',COMMON_PATH);

class UserController extends Controller
{
//Abandon function
/*
	public function signup(){
        if(session('?student_number'))
        {
            print_r($_COOKIE);
        }
        else
        {
            if(cookie('?student_number')&&cookie('?password'))
            {
                $User = D('User');
                    $student_number = cookie('student_number');
                    $password = cookie('password');
                    $result = $User->where("student_number={$student_number} and password={$password}")->find();
                    if($result) 
                    {
                        session('student_number',$User->student_number);
                        session('use_id',$User->id);//FK to file upload
                        cookie('student_number',$User->student_number,3600);
                        cookie('password',$User->password,3600);

                        print_r($_COOKIE);
                    }
                    else
                    {
                        $this->display();//Fake cookie?
                    }
            }
            else
            {
                $this->display();//First time to sign up or in?
            }
        }
    }
    
    public function signin(){
        if(session('?student_number'))
        {
            print_r($_COOKIE);
        //    $this->success('Should not be here');
        }
        else
        {
            if(cookie('?student_number')&&cookie('?password'))
            {
                $User = D('User');
                    $student_number = cookie('student_number');
                    $password = cookie('password');
                    $result = $User->where("student_number={$student_number} and password={$password}")->find();
                    if($result) 
                    {
                        session('student_number',$User->student_number);
                        session('use_id',$User->id);//FK to file upload
                        cookie('student_number',$User->student_number,3600);
                        cookie('password',$User->password,3600);
    //                $this->success('Successfully sign in');
                        print_r($_COOKIE);
                    }
                    else
                    {
                        $this->display();//Fake cookie?
                    }
            }
            else
            {
                $this->display();//First time to sign up or in?
            }
        }
    }
*/



        public function signinorup(){
        if(session('?use_id'))
        {
            print_r($_COOKIE);
            
                    echo("<a href='".U('Home/User/index')."'>Change password without recomfirmation</a><br>");
	                echo("<a href='".U('Home/User/logout')."'>Logout</a><br>");
	                echo("<a href='".U('Home/File/add')."'>Upload file</a><br>");
	                echo("<a href='".U('Home/File/index')."'>File list</a><br>");
        }
        else
        {
            if(cookie('?token'))
            {

                $info = auth_token(cookie('token'));
                if($info)
                {      
                    session('use_id',$info['id']);//Needed when file upload
                    print_r($_COOKIE);
                    
                    echo("<a href='".U('Home/User/index')."'>Change password without recomfirmation</a><br>");
	                echo("<a href='".U('Home/User/logout')."'>Logout</a><br>");
	                echo("<a href='".U('Home/File/add')."'>Upload file</a><br>");
	                echo("<a href='".U('Home/File/index')."'>File list</a><br>");
                }
                else
                {
                    $this->display();//Fake token
                }
            }
            else
            {
                $this->display();//First time to sign up or in?
            }
        }
    }
    
//Abandon function   
    /*
    public function add(){
        $User = D('User');
        
        $student_number = I('post.student_number');
        $password = I('post.password');
        if($User->create()) 
        {
            if($name = get_urp_name($student_number,$password))
            {
                $result = $User->add();
                if($result) 
                {                
                    session('student_number',$student_number);
                    session('use_id', $result);
                    cookie('student_number',$student_number,3600);
                    cookie('password',$password,3600);
    //                $this->success('Successfully sign in');
                    print_r($_COOKIE);
                }
                else
                {
                    $this->error('Can not insert to database');
                }
            }
            else
            {
                $this->error('Student ID not match with password in urp');
            }
        }
        else
        {
            $this->error('Can not create model');
        }
    }
    
    public function auth(){
        $User = D('User');
            $student_number = I('post.student_number');
            $password = I('post.password');
            $result = $User->where("password='{$password}' and student_number='{$student_number}'")->find();
            if($result) //auth passed
            {
                session('student_number',$User->student_number);
                session('use_id',$User->id);
                cookie('student_number',$User->student_number,3600);
                cookie('password',$User->password,3600);
//                $this->success('Successfully sign in');
                print_r($_COOKIE);
            }
            else
            {
//                $this->error('Not sign up yet');
//                Wrong password or not sign up yet
                var_dump($User);
            }
    }
    */
    
    
    
    public function addorauth(){
        $User = D('User');
            $student_number = I('post.student_number');
            $password = encode(I('post.password'),I('post.student_number'));
            $result = $User->where("student_number={$student_number}")->find();
            if($result) 
            {
                if($result['password'] == $password)//auth passed
                {
                    session('use_id',$User->id);
                    $token = update_token($User->id,1);
                    cookie('token',$token,3600);
                    print_r($_COOKIE);
                    echo("<a href='".U('Home/User/index')."'>Change password without recomfirmation</a><br>");
	                echo("<a href='".U('Home/User/logout')."'>Logout</a><br>");
	                echo("<a href='".U('Home/File/add')."'>Upload file</a><br>");
	                echo("<a href='".U('Home/File/index')."'>File list</a><br>");
                }
                else
                {
                    var_dump($result);//Wrong password
                }
            }
            else
            {
//                not sign up yet
//                var_dump($User);
                if($User->create()) 
                {
                    if($name = get_urp_name($student_number,I('post.password')))
                    {
                        $data['name']=$name;
                        $data['student_number']=$student_number;
                        $data['password']=$password;
                        $result = $User->add($data);
                        if($result) 
                        {                
                            session('use_id', $result);
                            $token = update_token($result,1);
                            cookie('token',$token,3600);
                            print_r($_COOKIE);
                            echo("<a href='".U('Home/User/index')."'>Change password without recomfirmation</a><br>");
	                        echo("<a href='".U('Home/User/logout')."'>Logout</a><br>");
                        }
                        else
                        {
                            $this->error('Can not insert to database');
                        }
                    }
                    else
                    {
                        $this->error('Student ID not match with password in urp');
                    }
                }
                else
                {
                    $this->error('Can not create model');
                }
            }
    }
    
    
    
    
    public function index(){
        if(session('?use_id'))
        {
            $User = M('User');
            $data = $User->where("id=".session('use_id'))->find();
            $this->data = $data;
            $this->display();
        }
        else
        {
            echo("Unauth");
        }
    }
    
    public function change(){
        if(session('?use_id'))
        {
            $User = M('User');
            if($User->create()) 
            {
                $result = $User->save();
                if($result)
                {
                    echo("Success");
                }
                else
                {
                    $this->error('Unable to write');
                }
            }
            else
            {
                $this->error($User->getError());
            }
        }
        else
        {
            echo("Unauth");
        }
    }
    public function logout()
    {
        delete_token(cookie('token'));
        session(null);
        cookie(null);
    }
}
