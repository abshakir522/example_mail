<?php

declare(strict_types=1);

namespace Drupal\example_mail\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Example mail form.
 */
final class ExampleMailForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'example_mail_example_mail';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['from_email'] = [
      '#type' => 'email',
      '#title' => $this->t('From'),
    ];

    $form['to_email'] = [
      '#type' => 'email',
      '#title' => $this->t('Send to'),
      // '#pattern' => '*@gmail.com',
    ];

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      // '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Send'),
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if (mb_strlen($form_state->getValue('message')) < 10) {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('Message should be at least 10 characters.'),
    //     );
    //   }
    // @endcode
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $site_name = \Drupal::config('system.site')->get('name');
    $module = 'example_mail';
    $key = 'example_mail_test';
    $to = $form_state->getValue('to_email');
    $from = $form_state->getValue('from_email');

    $mess = $form_state->getValue('message');
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $params['subject'] = t('Testing');
    $params['from'] = $from;
    $params['message'] = t($mess . " from @site_name", ['@site_name' => $site_name]);

    $result = \Drupal::service('plugin.manager.mail')->mail($module, $key, $to, $langcode, $params);
    if ($result['result'] == TRUE) {
      $this->messenger()->addStatus($this->t('The message has been sent.'));
      // $form_state->setRedirect('<front>');
    }
  }

}
