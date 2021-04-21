console.log('chargement de app.js');


let app = {

  selectInputSelector: 'select[name="categories[]"]',
  formSelector: '#categories-home-page',


  init: function () {
    console.log('initialization');

    //!il faut savoir si nous somme sur la page de sélection des catégorie de la home page
    let categoriesSelectElements = document.querySelectorAll(app.selectInputSelector);

    //!si nous sommes bien sur la page de sélection des catégories, alors nous initialisons les traitements de vérification de "non doublons"
    if (categoriesSelectElements.length) {
      app.initializeSelectHomeCategoriesControls();
    }

    //console.log(categoriesSelectElements);
  },

  initializeSelectHomeCategoriesControls: function () {
    let form = document.querySelector(app.formSelector);
    //!au submit on teste s'il y a des doublons
    form.addEventListener('submit', app.checkDoublon);
    console.log(form);
  },


  checkDoublon: function (evt) {

    console.log('initialization contrôle des doublons');

    //!récupérons toutes les catégories sélectionnées
    let categories = document.querySelectorAll(app.selectInputSelector);

    //!pour chaque catégorie, comptons combien de fois elle a été sélectionnée
    //*initialisons un "tableau associatif" qui aura en clé l'id de la catégorie et en valeur le nombre de fois où la catégorie a été sélectionnées
    let categoryCount = {};

    for (let selectInput of categories) {
      console.log(selectInput);

      //!récupération de l'id selectionné
      let categoryId = selectInput.value;
      console.log(categoryId);

      //!nous avons besoin d'un id de catégorie (sinon ceci signifie qu'il n'y a pas eu de catégorie de sélectionnée)
      if (categoryId) {
        //*si categoryCount[categoryId] n'existe pas, on l'initialise à 0
        if (typeof (categoryCount[categoryId]) === 'undefined') {
          categoryCount[categoryId] = 0;
        }

        //!incrémetion du compteur "d'apparition d'id"
        categoryCount[categoryId]++;
      }
      console.log(categoryCount);

      //!si catégorie a été selectionnée plus d'une fois, affichage d'un message d'erreur et on ne soumet pas le formulaire
      for(let categoryId in categoryCount) {
        let count = categoryCount[categoryId];
        if(count>1) {
          alert('Vous avez sélectionné plusieurs fois une même catégorie')
          evt.preventDefault();
          return;
        }
      }
    }

  }
};


//!lancement du module app lorsque la page a terminée d'être "générée"
document.addEventListener('DOMContentLoaded', app.init);







