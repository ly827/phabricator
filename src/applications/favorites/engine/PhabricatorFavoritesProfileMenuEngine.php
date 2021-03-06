<?php

final class PhabricatorFavoritesProfileMenuEngine
  extends PhabricatorProfileMenuEngine {

  protected function isMenuEngineConfigurable() {
    return true;
  }

  protected function getItemURI($path) {
    $object = $this->getProfileObject();
    $custom = $this->getCustomPHID();

    if ($custom) {
      return "/favorites/personal/item/{$path}";
    } else {
      return "/favorites/global/item/{$path}";
    }
  }

  protected function getBuiltinProfileItems($object) {
    $items = array();
    $viewer = $this->getViewer();

    $engines = PhabricatorEditEngine::getAllEditEngines();
    $engines = msortv($engines, 'getQuickCreateOrderVector');

    foreach ($engines as $engine) {
      foreach ($engine->getDefaultQuickCreateFormKeys() as $form_key) {
        $form_hash = PhabricatorHash::digestForIndex($form_key);
        $builtin_key = "editengine.form({$form_hash})";

        $properties = array(
          'name' => null,
          'formKey' => $form_key,
        );

        $items[] = $this->newItem()
          ->setBuiltinKey($builtin_key)
          ->setMenuItemKey(PhabricatorEditEngineProfileMenuItem::MENUITEMKEY)
          ->setMenuItemProperties($properties);
      }
    }

    return $items;
  }

}
