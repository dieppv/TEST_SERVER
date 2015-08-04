<?php
class DomainListHtml extends CWidget
{
    protected $domainList;
    protected $displayMode; // 1: dropDownList; 2: textField
    public $activeForm, $activeModel, $attribute;
    public $dropDownListHtmlOptions, $textFieldHtmlOptions;

    public function init() {
        $this->setDomainList();
        $this->setDisplayMode();
    }

    public function run() {
        if ( $this->displayMode === 1 ) {
            $htmlOptions = array(
                'empty' => '-- 選択してください --',
            );
            if ( is_array($this->dropDownListHtmlOptions) ) {
                $htmlOptions = CMap::mergeArray($htmlOptions, $this->dropDownListHtmlOptions);
            }
            echo $this->activeForm->dropDownList(
                $this->activeModel,
                $this->attribute,
                $this->domainList,
                $htmlOptions
            );
        } elseif ( $this->displayMode === 2 ) {
            $key = $this->activeModel->{$this->attribute};
            $htmlOptions = array(
                'class'    => 'form-field',
                'readonly' => 'readonly',
            );
            if ( is_array($this->textFieldHtmlOptions) ) {
                $htmlOptions = CMap::mergeArray($htmlOptions, $this->textFieldHtmlOptions);
            }
            echo CHtml::textField('tempo', $this->domainList[$key], $htmlOptions);
            echo $this->activeForm->hiddenField($this->activeModel, $this->attribute);
        }
    }

    protected function setDomainList() {
        $modelDomain = new LdapDomain();
        $this->domainList = $modelDomain->getList();

        return true;
    }

    protected function limitDomainList() {
        $ownDomain = Yii::app()->user->getState('dmID');
        $otherDomainIDs = Yii::app()->user->getState('otherDomainIDs');

        $newDomainList = array();
        $domainList = $this->domainList;

        foreach ( $domainList as $key => $value ) {
            if ( $key == $ownDomain || in_array($key, $otherDomainIDs) ) {
                $newDomainList[$key] = $value;
            }
        }

        $this->domainList = $newDomainList;
        return true;
    }

    private function setDisplayMode() {
        $userAuth = (int)Yii::app()->user->getState('userAuth');
        $dispMode = 2;

        if ( $userAuth === 90 ) {
            $dispMode = 1;
        } elseif ($userAuth === 30 ) {
            if ( Yii::app()->user->hasState('otherDomainIDs') ) {
                $this->limitDomainList();
                $dispMode = 1;
            }
        }

        $this->displayMode = $dispMode;
        return true;
    }
}
