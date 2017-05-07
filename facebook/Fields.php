<?php

namespace djeager\api\facebook;
use backend\modules\post\extensions\BaseParser;


class Fields extends BaseParser
{
    
    public function attributes()
    {
        return [

        
            'id',
            'cover',        //Information about the group's cover photo.
            'description',  //A brief description of the group.
            'email',        //The email address to upload content to the group. Only current members of the group can use this.
            'icon',         //The URL for the group's icon.
            'member_request_count', //The number of pending member requests. If the token is for an administrator, this is the total number of outstanding requests. If the token is for a group member, this will return the number of friends who have requested membership and also use the same app that is making the request.
            'name',         //The name of the group.
            'owner',        //The profile that created this group.
            'parent',       //The parent of this group, if it exists.
            'privacy',      //The privacy setting of the group. Possible values:CLOSED|OPEN|SECRET
            'updated_time', //The last time the group was updated (this includes changes in the group's properties and changes in posts and comments if the session user can see them).
        ];
    }
    public function scenarios()
    {
        return [
            'default' => ['data','paging',],
            'data' => $this->attributes(),
        ];
    }
    public function rules()
    {
        return [
//            [['id','description','name','owner','parent','privacy'],'string'],
//            [['member_request_count','updated_time'],'integer'],
//            [['icon'],'url'],
//            [['email'],'email'],
            //['data','vObjectCreate','params'=>['isArray'=>true,'fullName'=>$this->className(),'construct'=>['scenario'=>'data']]],
            //['data','djeager\validators\CreateObject','isArray'=>true,'fullName'=>$this->className(),'construct'=>['scenario'=>'data']],
        ];
    }
    public function attributeLabels()
    {
        return [];
    }
    public function getAlias(){
    	return ['title'=>'name'];
    } 
    public function scenarioDefault(){
        return $this->data;
    }
    public function getItem_id(){
    	return "{$this->owner}_{$this->id}";
    }
}