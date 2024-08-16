(function (Drupal) {
  Drupal.behaviors.exampleBehavior = {
    attach: function (context, settings) {
      var message = Drupal.t('Hello, world! Sanket');
      console.log(message);
    }
  };
})(Drupal);