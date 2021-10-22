<?php

namespace DwPhp\Library;

/**
  Class for define metatags e configurations html.
  Essa classe tem como principio definir as meta tags e configurações da pagina html.
*/

class head{
        /* url base do site. */
        private $base               =   ""; // https://dezwork.com
        /* configurações para site responsive. */
        private $viewport           =   "width=device-width, initial-scale=1.0, maximum-scale=1.0";

        /* Define o nome do autor da pagina. */
        private $metaAuthor         =   ""; // Cleber Bieleski

        /* Declara o direito autoral da pagina. */
        private  $metaCopyright     =   ""; //  2017 Dezwork Digital LTDA

        /* Contem uma descricao da pagina sobre o conteudo da pagina. */
        private  $metaDescription   =   ""; // descricao da pagina..

        /* Sao frases ou palavras separadas por virgulas, que definem as palavras chaves associadas ao conteudo da pagina. Normalmente, as keywords sao usadas pelos motores de busca. */
        private  $metaKeywords      =   ""; // criar site, criar e-commerce, lojavirtual..

        /* Indica o nome do software usado para criar a pagina como forma de medir a popularidade do produto. */
        private  $metaGenerator     =   ""; // SublimeText 2

        /* Diz para os servidores proxy refazer o cache da pagina depois de um tempo especifico. * Esta meta tag nao faz com que os motores de busca voltem para sua pagina. Eles fazem isso em periodos aleatorios. */
        private  $metaRevisitAfter  =   ""; // 7 days

        /* Esta tag classifica a pagina por censura, assim como no cinema, suporta os valores: - General: Para qualquer idade, - 14 years: Censura 14 anos, - Mature: Para pessoas acima de 18 anos */
        private  $metaRating        =   "General";

        /* define a meta Robots do sistema
            All         : Valor default, significa vazio, o robo de busca nao recebe nenhuma informacao.
            Index       : Os robos de busca podem incluir a pagina normalmente.
            Follow      : robos podem indexar a pagina e ainda seguir os links para outras paginas que ela contem.
            NoIndex     : Os links podem ser seguidos, mas a pagina nao e indexada.
            NoFollow    : A pagina e indexada, mas os links nao sao seguidos.
            None        : Os robos podem ignorar a pagina.
            NoArchive   : A pagina nao e arquivada (Apenas Google).
        */
        private  $metaRobots            =   "all";

        // Define o tipo de conteudo e tipo de codificacao de caracteres da pagina (sempre deve ser usada)
        private  $metaContentType   =   "text/html; charset=UTF-8";

        // Declara a uma ou mais linguagens do documento. Pode ser usada pelos motores de busca para categorizar a pagina por idioma.
        /*
        pt Português
        pt-br Português do Brasil
        en Inglês
        en-us Inglês dos EUA
        en-gb Inglês Britânico
        fr Francês
        de Alemão
        es Espanhol
        it Italiano 
        */ 
        private  $metaContentLanguage=  "pt-br";

        /* Reconhecida apenas pelo HTTP 1.1, esta tag aceita os seguintes valores:
            Public: O browser pode armazenar o cache do conteudo da pagina e compartilhar este cache com diferentes usuarios do mesmo browser.
            Private: E o contrario de public. O cache e armazenado para um usuario especifico (OBS: e possivel especificar usuarios apenas no Firefox e Opera)
            No-Cache: O browser nao cria cache para a pagina.
            No-Store: E feito um cache temporario, a pagina nao e arquivada.
        */
        private  $metaCacheControl  =   "Public";

        /* Faz com que o navegador nao armazene a pagina em cache. * A diferencia em realacao a  cache-control:no-cache e que pragma:no-cache e reconhecida por todas as versoes do HTTP. */
        private  $metaPragmaNoCache     =   ""; //no-cache

        /* Define a data e hora a partir do qual o documento deve ser considerado como expirado.
         * Uma data ilegal como, por exemplo "0" e considerada como "agora"
         * Configurar Expires  como "0" tambem e usado para forcar que o robo de busca faca uma nova checagem a cada visita.
        */
        private  $metaExpires       =   ""; //Sun, 29 may 2011 14:44:09 GMT

        /* Especifica um tempo em segundos para o browser atualizar a pagina.
         * Opcionalmente pode-se adicionar uma URL para a qual o browser sera redirecionado.
         * O metadado abaixo redireciona o browser para o site http://exemplo.com apos 15 segundos.
        */
        private  $metaRefresh       =   ""; //15;url=http://exemplo.com

        /* Elimina aquela pequena barra de opcoes que aparece sempre que passamos o mouse por cima de uma imagem no Internet Explorer. */
        private  $metaImagetoolbar  =   ""; //no

        /* Projeto Dublin Core - http://dublincore.org
         Na lista abaixo voce encontrara algumas tag  <meta>  cujo valor do atributo  name  comeca com DC .
         Estas tags fazem parte do projeto Dublin Core iniciado em 1995, cujo objetivo, segundo os organizadores, e melhorar a indexacao das paginas pelos motores de busca e dar mais flexibilidade aos autores.*/

        /* E o mesmo que "title", e deve conter o mesmo valor. */
        private  $metaTitle             =   ""; //Nome da pagina

        /* E o mesmo que "Author" e recomenda-se que tenha o mesmo valor. */
        private  $metaDCCreator         =   "";  // Cleber Bieleski

        /* E-mail para contato com o autor da pagina. */
        private  $metaDCCreatorAddress= "";  // email@exemplo.com

        /* E o mesmo que "Keywords" e recomenda-se que tenha o mesmo valor. */
        private  $metaDCSubject          =  "";  // criar site, criar e-commerce, lojavirtual..

        /* E mesmo que "Description" e recomenda-se que tenha o mesmo valor. */
        private  $metaDCDescription  =  "";  // descricao da pagina..

        /* Nome da organizacao responsavel pelo documento. */
        private  $metaDCPublisher    =  "";  // dezwork.com

        /* Normalmente, o webmaster responsavel pela pagina. */
        private  $metaCustodian  =  "";

        /* Data de criacao da pagina no formato AAAA-MM-DD. */
        private  $metaDCDateCreated  =  ""; // 2017-01-23

        /* Data da ultima modificacao do documento, importante para buscas por data. */
        private  $metaDCDateModified     =  ""; // 2017-01-23

        /* Especifica o tipo de dados contidos no documento. */
        private  $metaDCFormat      =   ""; // text/xhtml

        /* Determina a natureza ou genero do documento.   */
        private  $metaDCType            =   ""; // text.homepage.institucional

        /* URL do documento.   */
        private  $metaDCIdentifier          =   ""; // http://dezwork.com

        /* Determina a imagem do projeto*/
        private  $ogImage                 = "";

        /* Determina o favicon do projeto*/
        private  $favicon                 = "";

        /* Determina as tags de remarketing do projeto*/
        private  $tagsRemarketing         = "";

        /* Determina o analytics do projeto*/
        private  $tagGoogleAnalytics         = "";

         /* Determina outras tags do projeto*/
        private  $tagsPersonalize         = "";


    public function printHead(){
        $ret = '';
        if($this->getBase()!='')
            $ret .= '<base href="'.$this->getBase().'" />'."\n";

        if($this->getViewport())
            $ret .= '<meta name="viewport" content="'.$this->getViewport().'" />'."\n";

        if($this->getMetaContentType()!='')
            $ret .= '<meta http-equiv="Content-Type" content="'.$this->getMetaContentType().'" />'."\n";


        if($this->getMetaTitle()!='')
            $ret .= '<title>'.$this->getMetaTitle().'</title>'."\n";

        if($this->getMetaAuthor()!='')
            $ret .= '<meta name="author" content="'.$this->getMetaAuthor().'" />'."\n";

        if($this->getMetaCacheControl()!='')
            $ret .= '<meta http-equiv="cache-control" content="'.$this->getMetaCacheControl().'" />'."\n";

        if($this->getMetaContentLanguage()!='')
            $ret .= '<meta http-equiv="content-language" content="'.$this->getMetaContentLanguage().'" />'."\n";

        if($this->getMetaDescription()!='')
            $ret .= '<meta name="description" content="'.$this->getMetaDescription().'" />'."\n";

        if($this->getMetaKeywords()!='')
            $ret .= '<meta name="keywords" content="'.$this->getMetaKeywords().'" />'."\n";

        if($this->getMetaPragmaNoCache()!='')
            $ret .= '<meta http-equiv="pragma" content="'.$this->getMetaPragmaNoCache().'" />'."\n";

        if($this->getMetaRefresh()!='')
            $ret .= '<meta http-equiv="refresh" content="'.$this->getMetaRefresh().'" />'."\n";

        if($this->getMetaRobots()!='')
            $ret .= '<meta name="robots" content="'.$this->getMetaRobots().'" />'."\n";

        if($this->getMetaRobots()!='')
            $ret .= '<meta name="googlebot" content="'.$this->getMetaRobots().'" />'."\n";

        if($this->getMetaImagetoolbar()!='')
            $ret .= '<meta http-equiv="imagetoolbar" content="'.$this->getMetaImagetoolbar().'" />'."\n";

        if($this->getMetaGenerator()!='')
            $ret .= '<meta name="generator" content="'.$this->getMetaGenerator().'" />'."\n";

        if($this->getMetaRevisitAfter()!='')
            $ret .= '<meta name="revisit-after" content="'.$this->getMetaRevisitAfter().'" />'."\n";

        if($this->getMetaRating()!='')
            $ret .= '<meta name="rating" content="'.$this->getMetaRating().'" />'."\n";

        if($this->getMetaTitle()!='')
            $ret .= '<meta name="DC.title" content="'.$this->getMetaTitle().'" />'."\n";

        if($this->getMetaDCCreator()!='')
            $ret .= '<meta name="DC.creator" content="'.$this->getMetaDCCreator().'" />'."\n";

        if($this->getMetaDCCreatorAddress()!='')
            $ret .= '<meta name="DC.creator.address" content="'.$this->getMetaDCCreatorAddress().'" />'."\n";

        if($this->getMetaDCSubject()!='')
            $ret .= '<meta name="DC.subject" content="'.$this->getMetaDCSubject().'" />'."\n";

        if($this->getMetaDCDescription()!='')
            $ret .= '<meta name="DC.description" content="'.$this->getMetaDCDescription().'" />'."\n";

        if($this->getMetaCustodian()!='')
            $ret .= '<meta name="Custodian" content="'.$this->getMetaCustodian().'" />'."\n";

        if($this->getMetaDCDateCreated()!='')
            $ret .= '<meta name="DC.date.created" content="'.$this->getMetaDCDateCreated().'" />'."\n";

        if($this->getMetaDCDateModified()!='')
            $ret .= '<meta name="DC.date.modified" content="'.$this->getMetaDCDateModified().'" />'."\n";

        if($this->getMetaDCIdentifier()!='')
            $ret .= '<meta name="DC.Identifier" content="'.$this->getMetaDCIdentifier().'" />'."\n";

        if($this->getMetaDCFormat()!='')
            $ret .= '<meta name="DC.format" content="'.$this->getMetaDCFormat().'" />'."\n";

        if($this->getMetaDCType()!='')
            $ret .= '<meta name="DC.type" content="'.$this->getMetaDCType().'" />'."\n";

        if($this->getOgImage()!='')
            $ret .= '<meta property="og:image" content="'.$this->getOgImage().'">'."\n";

        if($this->getFavicon()!='')
            $ret .= '<link rel="icon" type="image/png"  href="'.$this->getFavicon().'">'."\n";

        return $ret;
    }
    public function printHeadEnd(){
        $ret =  '';
        if($this->getTagsRemarketing()!='')
            $ret = $this->getTagsRemarketing()."\n";

        if($this->getTagGoogleAnalytics()!='')
            $ret .= $this->getTagGoogleAnalytics()."\n";

        if($this->getTagsPersonalize()!='')
            $ret .= $this->getTagsPersonalize()."\n";
        
        return $ret;
    }

    public function getBase(){
        return $this->base;
    }

    /**
     *
     * @return self
     */
    public function setBase($base){
        $this->setMetaDCIdentifier($base);
        $this->base = $base;
        return $this;
    }

    public function getViewport(){
        return $this->viewport;
    }

    /**
     *
     * @return self
     */
    public function setViewport($viewport){
        $this->viewport = $viewport;
        return $this;
    }

    public function getMetaAuthor(){
        return $this->metaAuthor;
    }

    /**
     *
     * @return self
     */
    public function setMetaAuthor($metaAuthor){
        $this->metaAuthor = $metaAuthor;
        return $this;
    }

    public function getMetaCopyright(){
        return $this->metaCopyright;
    }

    /**
     *
     * @return self
     */
    public function setMetaCopyright($metaCopyright){
        $this->metaCopyright = $metaCopyright;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaDescription(){
        return $this->metaDescription;
    }

    /**
     *
     * @return self
     */
    public function setMetaDescription($metaDescription){
        $this->metaDescription = $metaDescription;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaKeywords(){
        return $this->metaKeywords;
    }

    /**
     *
     * @return self
     */
    public function setMetaKeywords($metaKeywords){
        $this->metaKeywords= $metaKeywords;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaGenerator(){
        return $this->metaGenerator;
    }

    /**
     * @return self
     */
    public function setMetaGenerator($metaGenerator){
        $this->metaGenerator  = $metaGenerator;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaRevisitAfter(){
        return $this->metaRevisitAfter;
    }

    /**
     * @return self
     */
    public function setMetaRevisitAfter ($metaRevisitAfter){
        $this->metaRevisitAfter  = $metaRevisitAfter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaRating(){
        return $this->metaRating;
    }

    /**
     * @return self
     */
    public function setMetaRating($metaRating){
        $this->metaRating = $metaRating;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaRobots(){
        return $this->metaRobots;
    }


    /**
     * @return self
     */
    public function setMetaRobots($metaRobots){
        $this->metaRobots = $metaRobots;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getMetaContentType(){
        return $this->metaContentType;
    }

    /**
     * @return self
     */
    public function setMetaContentType($metaContentType){
        $this->metaContentType = $metaContentType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaContentLanguage(){
        return $this->metaContentLanguage;
    }

    /**
     * @return self
     */
    public function setMetaContentLanguage($metaContentLanguage){
        $this->metaContentLanguage = $metaContentLanguage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaCacheControl(){
        return $this->metaCacheControl;
    }

    /**
     * @return self
     */
    public function setMetaCacheControl($metaCacheControl){
        $this->metaCacheControl = $metaCacheControl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaPragmaNoCache(){
        return $this->metaPragmaNoCache;
    }

    /**
     * @return self
     */
    public function setMetaPragmaNoCache($metaPragmaNoCache){
        $this->metaPragmaNoCache = $metaPragmaNoCache;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaExpires(){
        return $this->metaExpires;
    }

    /**
     * @return self
     */
    public function setMetaExpires($metaExpires){
        $this->metaExpires = $metaExpires;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaRefresh(){
        return $this->metaRefresh;
    }

    /**
     * @return self
     */
    public function setMetaRefresh($metaRefresh){
        $this->metaRefresh = $metaRefresh;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaImagetoolbar(){
        return $this->metaImagetoolbar;
    }

    /**
     * @return self
     */
    public function setMetaImagetoolbar($metaImagetoolbar){
        $this->metaImagetoolbar = $metaImagetoolbar;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaTitle(){
        return $this->metaTitle;
    }

    /**
     * @return self
     */
    public function setMetaTitle($metaTitle){
        $this->metaTitle = $metaTitle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaDCCreator(){
        return $this->metaDCCreator;
    }

    /**
     * @return self
     */
    public function setMetaDCCreator($metaDCCreator){
        $this->metaDCCreator = $metaDCCreator;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaDCCreatorAddress(){
        return $this->metaDCCreatorAddress;
    }

    /**
     * @return self
     */
    public function setMetaDCCreatorAddress($metaDCCreatorAddress){
        $this->metaDCCreatorAddress = $metaDCCreatorAddress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaDCSubject(){
        return $this->metaDCSubject;
    }

    /**
     * @return self
     */
    public function setMetaDCSubject($metaDCSubject){
        $this->metaDCSubject = $metaDCSubject;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaDCDescription(){
        return $this->metaDCDescription;
    }

    /**
     * @return self
     */
    public function setMetaDCDescription($metaDCDescription ){
        $this->metaDCDescription     = $metaDCDescription;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaDCPublisher(){
        return $this->metaDCPublisher;
    }

    /**
     * @return self
     */
    public function setMetaDCPublisher($metaDCPublisher){
        $this->metaDCPublisher  = $metaDCPublisher;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaCustodian(){
        return $this->metaCustodian;
    }

    /**
     * @return self
     */
    public function setMetaCustodian($metaCustodian){
        $this->metaCustodian     = $metaCustodian;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaDCDateCreated(){
        return $this->metaDCDateCreated;
    }

    /**
     * @return self
     */
    public function setMetaDCDateCreated($metaDCDateCreated){
        $this->metaDCDateCreated= $metaDCDateCreated;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaDCDateModified(){
        return $this->metaDCDateModified;
    }

    /**
     * @return self
     */
    public function setMetaDCIdentifier($metaDCIdentifier){
        $this->metaDCIdentifier= $metaDCIdentifier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaDCIdentifier(){
        return $this->metaDCIdentifier;
    }

    /**
     * @return self
     */
    public function setMetaDCDateModified($metaDCDateModified){
        $this->metaDCDateModified = $metaDCDateModified;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaDCFormat(){
        return $this->metaDCFormat;
    }

    /**
     * @return self
     */
    public function setMetaDCFormat ($metaDCFormat){
        $this->metaDCFormat  = $metaDCFormat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaDCType(){
        return $this->metaDCType;
    }

    /**
     * @return self
     */
    public function setMetaDCType($metaDCType){
        $this->metaDCType = $metaDCType;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getOgImage(){
        return $this->ogImage;
    }

    /**
     * @return self
     */
    public function setOgImage($ogImage){
        $this->ogImage = $ogImage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFavicon(){
        return $this->favicon;
    }

    /**
     * @return self
     */
    public function setFavicon($favicon){
        $this->favicon = $favicon;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTagsRemarketing(){
        return $this->tagsRemarketing;
    }

    /**
     * @return self
     */
    public function setTagsRemarketing($tagsRemarketing){
        $this->tagsRemarketing = $tagsRemarketing;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getTagGoogleAnalytics(){
        return $this->tagGoogleAnalytics;
    }

    /**
     * @return self
     */
    public function setTagGoogleAnalytics($tagGoogleAnalytics){
        $this->tagGoogleAnalytics = $tagGoogleAnalytics;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getTagsPersonalize(){
        return $this->tagsPersonalize;
    }

    /**
     * @return self
     */
    public function setTagsPersonalize($tagsPersonalize){
        $this->tagsPersonalize = $tagsPersonalize;
        return $this;
    }
}