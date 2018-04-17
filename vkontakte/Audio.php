<?php

namespace djeager\api\vkontakte;


class Audio extends Vk
{
    protected $template = '<!--audio-->{player}<!--/audio-->';

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            /** data scenario */
            /*v4.104*/
            'aid', 'owner_id', 'artist', 'title', 'duration', 'url', 'lyrics_id', 'album', 'genre',
            /** getById scenario */
            'audios',
        ]);
    }

    public function scenarios()
    {
        $p = parent::scenarios();
        $s = [
            /*v4.104*/
            'data' => ['aid', 'owner_id', 'artist', 'title', 'duration', 'url', 'lyrics_id', 'album', 'genre',],

            'getById' => ['audios', 'v'],
        ];
        return array_merge($p, $s);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            /** data scenario */
            /*v4.104*/
            [['aid', 'owner_id', 'lyrics_id', 'album', 'genre'], 'integer'],
            [['artist', 'title',], 'string'],
            [['url'], 'url'],
            ['aid', 'validAid'],

            /** getById scenario */
            ['audios', 'string'],
        ]);
    }

    public function getAlias()
    {
        return [
            'preview' => 'player',
            'description' => 'title',
        ];
    }

    public function validAid($a, $p)
    {
        if ($this->scenario == 'data') {
            if (isset(Vk::$lateLoad[$this->className()]['attributes']['audios']))
                $pre = Vk::$lateLoad[$this->className()]['attributes']['audios'];
            else $pre = null;
            $vname = $this->owner_id;
            $vname .= '_' . $this->aid;
            //if( isset($this->access_key))$fvid=$vname.'_'.$this->access_key;
            Vk::$lateLoad[$this->className()]['scenario'] = 'getById';
            Vk::$lateLoad[$this->className()]['attributes']['audios'] = $vname . ($pre ? ',' . $pre : '');
            Vk::$lateLoad[$this->className()]['return']['templateId'] = ['owner_id', '_', 'aid'];
            Vk::$lateLoad[$this->className()]['return']['to'][$vname] = $this;

        }

        return $this->aid;
    }

    public function scenarioGetById()
    {
        return $this->getData();
    }

    public function scenarioData(Vk $obj = null)
    {
        if ($this->scenario == 'data') {
            return $this->attributes;
        } elseif ($this->scenario == 'default') {
            return $this->attributes;
        } else {
            $d = parent::getData($obj);
            return $this->data = $d['response'];
        }
        $this->load(['Audio' => $g['response'][1]]);

    }

    public function getHtml()
    {
        return parent::getHtml();

    }

    public function getPlayer($val = null)
    {
        return "<audio controls>    
            <source src='{$this->url}' type='audio/mpeg'>
        </audio>";

        //return "<iframe class='embed-responsive-item' src='{$this->url}'></iframe>";
    }
}

?>