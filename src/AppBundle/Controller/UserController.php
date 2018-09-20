<?php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\UserInformation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     *  @Route("/", name="user_page")
     *
     @Security("is_granted('ROLE_USER')")
     */
    public function afficheInformation()
    {   
        $username = $this->getUser()->getUserName(); 
        $id = $this->getUser()->getId(); 

        //Vérifier les informations de teletubbie qui sont existés dans la table user_information ou pas.
        $em=$this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('AppBundle:UserInformation');
        $user = $repository->findOneBy(array('user'=>$id));

        //Si dans la table user_information, il n'existe pas les informations de cet user, ses information sont remplis automatiquement par dèault
        if(sizeof($user) == 0){
            $userInformation = new UserInformation();
            $userInformation->setAge(0);
            $userInformation->setRace("");
            $userInformation->setFamille("");
            $userInformation->setNourriture("");
            $userInformation->setUser($this->getUser());
            $em->persist($userInformation);
            $em->flush();
        }
        //Chercher les informations 
        $user = $repository->findOneBy(array('user'=>$id));
        
        //chercher les amis
        $sql = "SELECT * FROM relation r, fos_user f, user_information i WHERE r.user2 = f.id AND i.id_user = r.user2 AND r.user1 = :id ";
        $em=$this->getDoctrine()->getEntityManager();
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('id', $id);
        $statement->execute();
        $friends = $statement->fetchAll();

        $em=$this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('AppBundle:User');
        $users = $repository->findAll();
        
        //Envoyer le resultat (ses informations et les informations des amis de cet user)
        return $this->render('default\index.html.twig', array(
            'username'=>$username,
            'age'=>$user->getAge(),
            'race'=>$user->getRace(),
            'famille'=>$user->getFamille(),
            'nourriture'=>$user->getNourriture(),
            'friends'=>$friends,
            'users'=>$users
        ));
    }


    /**
     * @Route("/information", name="addInformation")
     */
    public function enregitrerUser(Request $request)
    {   
        if($request->getMethod()=='POST')
        {
            $id = $this->getUser()->getId(); 
            //Chercher user dans la table fos_user
            $em=$this->getDoctrine()->getEntityManager();
            $repository = $em->getRepository('AppBundle:User');
            $user = $repository->findOneBy(array('id'=>$id));

            //Chercher les information de cet user
            $repository = $em->getRepository('AppBundle:UserInformation');
            $userInformation = $repository->findOneBy(array('user'=>$id));
            
            //Récupérer les données à partir du form
            $age=$request->request->get('age');
            $race=$request->request->get('race');
            $famille=$request->request->get('famille');
            $nourriture=$request->request->get('nourriture');
            if($age){
                $userInformation->setAge($age);
            }
            if($race){
                $userInformation->setRace($race);
            }
            if($famille){
                $userInformation->setFamille($famille);
            }
            if($nourriture){
                $userInformation->setNourriture($nourriture);
            } 
            $userInformation->setUser($user);
            //Enregistrer
            $em->persist($userInformation);
            $em->flush();
            return new Response("Add Success");
        }
    }
    /**
     * @Route("/delete_friend/{id_user2}", name="delete_friend")
     */
     public function deleteAction($id_user2)
     {
        $id_user1 = $this->getUser()->getId();
        $sql = "DELETE FROM relation WHERE user1 = :id_user1 AND user2 = :id_user2";
        $em=$this->getDoctrine()->getEntityManager();
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue(':id_user1', intval($id_user1));
        $statement->bindValue(':id_user2', intval($id_user2));
        
        $statement->execute();
         return new Response('friend est supprimé');
     }   
    /**
     * @Route("/addFriend", name="addFriend")
     */
    public function addFriend(Request $request)
    {  
        $id_user1 = $this->getUser()->getId();
        $id_user2 = $event = $_POST['friend'];

        //Chercher si cette relation entre eux existe dans la table relation
        $sql = "SELECT * FROM relation WHERE user1 = :id_user1 AND user2 = :id_user2";
        $em=$this->getDoctrine()->getEntityManager();
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue(':id_user1', intval($id_user1));
        $statement->bindValue(':id_user2', intval($id_user2));
        $statement->execute();
        $friends = $statement->fetchAll();
        
        //Si cette relation n'exsiste pas, on va l'ajouter
        if(sizeof($friends)==0){
            $sql = "INSERT INTO relation (user1, user2) VALUES (:id_user1,:id_user2)";
            $em=$this->getDoctrine()->getEntityManager();
            $statement = $em->getConnection()->prepare($sql);
            $statement->bindValue(':id_user1', intval($id_user1));
            $statement->bindValue(':id_user2', intval($id_user2));
            $statement->execute();
            return new Response('Ajouté');
        }
        //Sinon, envoyer la réponse "déjà exist"
        else{
            return new Response('Déjà existé');
        }
        
    }
}