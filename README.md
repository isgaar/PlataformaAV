<h1 align="center">
  <img src="public/images/logoAV.png" alt="Logo PlataformaAV" width="150" /><br/>
  Le damos la bienvenida a PlataformaAV
</h1>

<p align="center">
  <img alt="Versión" src="https://img.shields.io/badge/version-1.0.0-blue.svg?cacheSeconds=2592000" />
</p>

> Plataforma de aprendizaje químico molecular

---

## 📋 Requisitos Previos

Antes de instalar el proyecto, asegúrate de tener disponibles las siguientes herramientas y extensiones:

- PHP 8.2 con los siguientes módulos:
  - cli, fpm, pgsql, mbstring, xml, curl, zip, bcmath, gd, intl, soap, readline, opcache
- Composer
- Git
- Node.js y NPM
- unzip y curl

Estos componentes permiten ejecutar Laravel, gestionar dependencias, compilar recursos y trabajar con la base de datos.

---

## 🚀 Instalación

1. **Instalar dependencias de backend:**

   ```bash
   composer install
   ```

2. **Configurar el entorno:**

   * Copiar el archivo .env.example a .env:
   ```bash
   cp .env.example .env
   ```

   * Generar la clave de aplicación:

   ```bash
   php artisan key:generate
   ```

3. **Configurar Passport (autenticación API):**

   ```bash
   php artisan passport:install
   ```

4. **Configurar Passport (autenticación API):**

   ```bash
   php artisan migrate --seed
   ```

## 👤 Por:

**Ismael Gaspar**

> GitHub: [@isgaar](https://github.com/isgaar)
>
> ```
> .       :::::  .   ::::::        
>     .@@@@###%@@  @@@%###@        
>     @+#@#######%%#######%++@@    
>  .  @@@######@#@@#######%@#+@    
>   . @#######@#++@@########%@     
>   @@%#####%@++++++@%########@    
>   @#####@@  @@++@@@  @@######@@  
>  ..@####@#   #++@@#   #@######%@ 
>   @####@@   @++++@   @+@%######@ 
>  @####@#+++++###++++++++@######@ 
>  @@ @%@+++++++++++++++++%#####@  
>    . @@@++++++++++@++++@%@@##@@. 
>      .@%@@@@@%%%%@@@@@%%#@@@@  . 
>  .   @@@@%@**%+++%***%%@@@       
>  .    @%%@**%@%%%***@%%%#.       
> .    @#@@**%@**%@**@@%%%% ..     
>    @@++##*%@***@**%@*%@@+@@   .  
>    @+++@@@@****%%@@%**@+++@@.    
>     @@@***************@++++@     
>     @@***%@@%********%@@++@@    .
>  @@@@****%@@*********@   .       
>  @.%%%@*@@%*********@            
>   @@@  @@%@@%******@  .    .     
>         @ %%%@***@@    .         
>          @@@  @@@                
>            -----                 
> ```


## 📄 Licencia

Este proyecto está bajo la licencia [MIT](LICENSE).




