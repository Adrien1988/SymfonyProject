<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Contact;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\ContactType;
use App\Form\ProduitType;
use App\Entity\Commentaire;
use App\Form\CategorieType;
use App\Form\CommentaireType;
use App\Form\AdminProduitType;
use App\Repository\UserRepository;
use App\Repository\ContactRepository;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Notification\ContactNotification;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class AdminController extends AbstractController
{
  #[Route('/admin', name: 'app_admin')]
  public function index(): Response
  {
    return $this->render('admin/index.html.twig', [
      'controller_name' => 'AdminController',
    ]);
  }

  /////////////////////// PRODUITS ////////////////////////////////////

  /**
   * @Route("/admin/produit", name="admin_produit")
   */
  public function adminProduit(ProduitRepository $repo, EntityManagerInterface $manager)
  {

    $colonnes = $manager->getClassMetadata(Produit::class)->getFieldNames();

    $produit = $repo->findAll();

    return $this->render("admin/admin_produit.html.twig", [
      'produit' => $produit,
      'colonnes' => $colonnes
    ]);
  }

  /**
   * @Route("/admin/produit/new", name="admin_new_produit")
   * @Route("/admin/produit/edit/{id}", name="admin_edit_produit")
   */
  public function formProduit(Produit $produit = null, Request $request, EntityManagerInterface $manager)
  {
    if (!$produit) {
      $produit = new Produit;
    }
    $form = $this->createForm(AdminProduitType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $manager->persist($produit);
      $manager->flush();
      $this->addFlash("success", "Produit enregistré !");
      return $this->redirectToRoute('admin_produit');
    }
    return $this->render("admin/form_produit.html.twig", [
      "formProduit" => $form->createView(),
      "editMode" => $produit->getId() !== null
    ]);
  }

  /**
   * @Route("/admin/produit/delete/{id}", name="delete_produit")
   */
  public function deleteProduit(Produit $produit, EntityManagerInterface $manager)
  {
    $manager->remove($produit);
    // remove() prépare la suppression
    $manager->flush();
    $this->addFlash("success", "Le produit a bien été supprimé !");
    return $this->redirectToRoute('admin_produit');
  }


  ///////////////////////////// CATEGORIES ///////////////////////////

/**
   * @Route("/admin/categorie", name="admin_categorie")
   */
  public function adminCategorie(CategorieRepository $repo, EntityManagerInterface $manager)
  {

    $colonnes = $manager->getClassMetadata(Categorie::class)->getFieldNames();

    $categorie = $repo->findAll();

    return $this->render("admin/admin_categorie.html.twig", [
      'categorie' => $categorie,
      'colonnes' => $colonnes
    ]);
  }

  /**
   * @Route("/admin/categorie/new", name="admin_new_categorie")
   * @Route("/admin/categorie/edit/{id}", name="admin_edit_categorie")
   */
  public function formCategorie(EntityManagerInterface $manager, Request $request, Categorie $categorie = null)
  {
    if (!$categorie) {
      $categorie = new Categorie;
      // $category->setCreatedAt(new \DateTime());
    }
    $form = $this->createForm(CategorieType::class, $categorie);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $manager->persist($categorie);
      $manager->flush();
      $this->addFlash("success", "Categorie enregistré !");
      return $this->redirectToRoute('admin_categorie');
    }
    return $this->render("admin/form_categorie.html.twig", [
      "formCategorie" => $form->createView(),
      "editMode" => $categorie->getId() !== null
    ]);
  }
  /**
   * @Route("/admin/categorie/delete/{id}", name="delete_categorie")
   */
  public function deleteCategorie(Categorie $categorie, EntityManagerInterface $manager)
  {
    $manager->remove($categorie);
    // remove() prépare la suppression
    $manager->flush();
    $this->addFlash("success", "La catégorie a bien été supprimé !");
    return $this->redirectToRoute('admin_categorie');
  }


  //////////////////////////// Contact ////////////////////////////////////////////////////

  /**
   * @Route("/admin/contact", name="admin_contact")
   */
  public function adminContact(ContactRepository $repo, EntityManagerInterface $manager)
  {

    $colonnes = $manager->getClassMetadata(Contact::class)->getFieldNames();

    $contact = $repo->findAll();

    return $this->render("admin/admin_contact.html.twig", [
      'contact' => $contact,
      'colonnes' => $colonnes
    ]);
  }

  /**
   * @Route("/admin/contact/new", name="admin_new_contact")
   * @Route("/admin/contact/edit/{id}", name="admin_edit_contact")
   */
  public function formContact(EntityManagerInterface $manager, Request $request, Contact $contact = null)
  {
    if (!$contact) {
      $contact = new Contact;
      $contact->setCreatedAt(new \DateTime());
    }
    $form = $this->createForm(ContactType::class, $contact);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $manager->persist($contact);
      $manager->flush();
      $this->addFlash("success", "Message enregistré !");
      return $this->redirectToRoute('admin_contact');
    }
    return $this->render("admin/form_contact.html.twig", [
      "formContact" => $form->createView(),
      "editMode" => $contact->getId() !== null
    ]);
  }

  /**
   * @Route("/admin/contact/delete/{id}", name="delete_contact")
   */
  public function deleteContact(Contact $contact, EntityManagerInterface $manager)
  {
    $manager->remove($contact);
    // remove() prépare la suppression
    $manager->flush();
    $this->addFlash("success", "Cette prise de contact a bien été supprimée !");
    return $this->redirectToRoute('admin_contact');
  }


///////////////////////////// COMMENTAIRES /////////////////////////////

/**
   * @Route("/admin/commentaire", name="admin_commentaire")
   */
  public function adminCommentaire(CommentaireRepository $repo, EntityManagerInterface $manager)
  {

    $colonnes = $manager->getClassMetadata(Commentaire::class)->getFieldNames();

    $commentaire = $repo->findAll();

    return $this->render("admin/admin_commentaire.html.twig", [
      'commentaire' => $commentaire,
      'colonnes' => $colonnes
    ]);
  }

  /**
   * @Route("/admin/commentaire/new", name="admin_new_commentaire")
   * @Route("/admin/commentaire/edit/{id}", name="admin_edit_commentaire")
   */
  public function formCommentaire(EntityManagerInterface $manager, Request $request, Commentaire $commentaire = null)
  {
    if (!$commentaire) {
      $commentaire = new Commentaire;
      $commentaire->setCreatedAt(new \DateTime());
    }
    $form = $this->createForm(CommentaireType::class, $commentaire);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
     
      $manager->persist($commentaire);
      $manager->flush();
      $this->addFlash("success", "Commentaire enregistré !");
      return $this->redirectToRoute('admin_commentaire');
    }
    return $this->render("admin/form_commentaire.html.twig", [
      "formCommentaire" => $form->createView(),
      "editMode" => $commentaire->getId() !== null
    ]);
  }

   
  /**
   * @Route("/admin/commentaire/delete/{id}", name="delete_commentaire")
   */
  public function deleteCommentaire(Commentaire $commentaire = null, EntityManagerInterface $manager)
  {
    $manager->remove($commentaire);
    // remove() prépare la suppression
    $manager->flush();
    $this->addFlash("success", "Ce commentaire a bien été supprimé !");
    return $this->redirectToRoute('admin_commentaire');
  }

////////////////////////////////// Utilisateurs /////////////////////////////

/**
   * @Route("/admin/user", name="admin_user")
   */
  public function adminUser(UserRepository $repo, EntityManagerInterface $manager)
  {

    $colonnes = $manager->getClassMetadata(User::class)->getFieldNames();

    $user = $repo->findAll();

    return $this->render("admin/admin_user.html.twig", [
      'user' => $user,
      'colonnes' => $colonnes
    ]);
  }

  /**
   * @Route("/admin/user/new", name="admin_new_user")
   * @Route("/admin/user/edit/{id}", name="admin_edit_user")
   */
  public function formUser(EntityManagerInterface $manager, Request $request, User $user = null, UserPasswordHasherInterface $encoder)
  {
    if (!$user) {
      $user = new User;
    }
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $mdp = $form->get('plainPassword')->getData();
      // je récupère la saisie de l'utilisateur dans le champ du mot de passe
      if (!$user->getId() && !$mdp) {
        // si c'est un nouvel utilisateur sans mot de passe : erreur => je recharge la page
        $this->addFlash('error', "Un nouvel utilisateur a besoin d'un mot de passe !");
        return $this->redirectToRoute('admin_new_user');
    }

    // sinon si je rentre un mot de passe : j'encode le mot de passe
    else if ((!$user->getId() || $user->getId()) && $mdp)
        $user->setPassword($encoder->hashPassword($user, $mdp));

    // si c'est un nouvel utilisateur ou qu'on modifie le mot de passe d'un utilisateur existant
    // alors on hashe le nouveau mdp
    // si on édite un utilisateur sans toucher à son mot de passe, il gardera son ancien mot de passe

      $manager->persist($user);
      $manager->flush();
      $this->addFlash("success", "Utilisateur enregistré !");
      return $this->redirectToRoute('admin_user');
    }
    return $this->render("admin/form_user.html.twig", [
      "formUser" => $form->createView(),
      "editMode" => $user->getId() !== null
    ]);
  }

   
  /**
   * @Route("/admin/user/delete/{id}", name="delete_user")
   */
  public function deleteUser(User $user = null, EntityManagerInterface $manager)
  {
    $manager->remove($user);
    // remove() prépare la suppression
    $manager->flush();
    $this->addFlash("success", "Cet utilisateur a bien été supprimé !");
    return $this->redirectToRoute('admin_user');
  }


}
