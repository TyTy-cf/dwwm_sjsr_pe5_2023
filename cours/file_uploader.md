### Etapes pour le FileUploader

- Définir dans le .env la clé "UPLOAD_DIR"
- Déclarer les variables à injecter dans le "config/services.yaml", sous la clé "bind" :

```yaml
bind:
    $publicUploadsDir: '%kernel.project_dir%/public/%env(UPLOAD_DIR)%'
    $uploadsDir: '%env(UPLOAD_DIR)%'
```

Les variables $publicUploadsDir & $uploadsDir sont dorénavant des variables dans des services :

```php
public function __construct(
    private string $publicUploadsDir,
    private string $uploadsDir
) { }
```


- A l'intérieur du FileUploader, il faut appeler la méthode "uploadFile" :
```php
public function uploadFile(UploadedFile $uploadedFile, string $dir = ''): string
{
    // Récupère le chemin où déplacer le fichier uploadé sur le serveur
    $destination = $this->publicUploadsDir.$dir;
    // Récupère le nom réel du fichier (et non un .tmp)
    $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
    // Recréer un nom au fichier avec un uniqid au cas où l'utilisateur upload plusieurs fois un fichier de même nom
    $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
    // Déplace le fichier uploadé dans le dossier souhaité et le renomme par le nom créé précédemment
    $uploadedFile->move($destination, $newFilename);
    // Renvoie une URL de l'image à utiliser depuis l'URL (http://127.0.0.1:8000/uploads/xxx.jpg)
    // Cette chaîne de caractères sera celle à donner en propriété à l'objet
    return '/'.$this->uploadsDir.$dir.'/'.$newFilename;
} 
```
- Dans le FormType :

```php
->add('image', FileType::class,[
    'required' => !$options['is_edit'],
    'mapped' => false,
    'constraints' => [
        new File(
            mimeTypes: ['image/png', 'image/jpeg'],
            mimeTypesMessage: 'Déposer seulement un .jpg ou .png'
        )
    ]
])
```

Bien mettre le champ à "required" false et "mapped" false
(Afin qu'il ne soit plus traité directement par Symfony, l'intérêt aussi est que si l'utilisateur ne dépose pas de fichier alors le champ du form sera à null)

- Injectez le FileUploader fournit, dans le contrôleur où vous souhaitez l'utiliser
- On vérifit si le fichier existe dans le submit du formulaire, et on appelle le FileUploader (variable : $fileUploader) à ce moment là :

```php
if ($form->isSubmitted() && $form->isValid()) {
    /** @var UploadedFile $uploadedFile */
    $uploadedFile = $form->get('image')->getData();
    if ($uploadedFile !== null) {
        $category->setImage(
            $fileUploader->uploadFile(
                $uploadedFile, // => UploadedFile
                '/category'
            )
        );
    }
}
```
