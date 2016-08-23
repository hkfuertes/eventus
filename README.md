EVENTUS WEB API
====================

Para la interacción con el sistema, se plantea una dinámica tipo sesión. La primera interacción será para obtener un token, que servirá como contraseña para las siguientes interacciones. Para cada petición se espera que se le proporcione al sistema, un ***username*** y un ***token*** que valide el usuario en *uso* y permita al sistema hacer las operaciones pedidas.

Por eso, la primera función a ejecutar será siempre la validación, que es la que se encarga de darle a la aplicación cliente un token valido. Si el usuario es correcto y la contraseña también, y el usuario no tiene ningún token valido en la base de datos, ***validate()*** se encargara de crear el token para nosotros.

Funciones de Usuario
====================

Validación de usuario
---------------------

Validar usuario contra el servicio web:

    http://<eventus_api>/<app_token>/user/validate/<username>

Necesita de variables POST con parámetros no visibles a simple vista:

-   ***username*** con el nombre de usuario.
-   ***password*** con la contraseña.

Esto devolverá un JSON de la forma:

`{`
`   `**`success`**`: true,`
`   `**`user`**`: {`
`       `**`username`**`: "hkfuertes",`
`       `**`token`**`: "3a6540079128b8b19f115783ec30e1d2"`
`   }`
`}`

En caso de que la **aplicación no este registrada**, y que por tanto el token de la aplicación sea inválido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de no se pueda validar el usuario contra la base de datos como usuario o contraseña **incorrecta**, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

*(El token se requerirá para futuras interacciones con el sistema)*

Comprobar que el Usuario esta registrado
----------------------------------------

Validar usuario contra el servicio web:

    http://<eventus_api>/<app_token>/user/token/check/<username>

Necesita de variables POST con parámetros no visibles a simple vista:

-   ***username*** con el nombre de usuario.
-   ***password*** con la contraseña.

Esto devolverá un JSON de la forma:

`{ `**`success`**`: true }`

En caso de que la **aplicación no este registrada**, y que por tanto el token de la aplicación sea inválido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de no se pueda validar el usuario no tenga ese token o no esté activo, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

Recuperar la información de un Usuario
--------------------------------------

Recuperar la información de un usuario de la base de datos:

    http://<eventus_api>/<app_token>/user/info/<who>

El parámetro ***who*** hace referencia al nombre usuario del que se quiere obtener información: Además de este parámetro se requiere de variables POST:

-   ***token*** que contendrá el token recibido al validar el usuario.
-   ***username*** que contendrá el nombre de usuario.

Esto devolverá un JSON de la forma:

`{`
`   `**`success`**`: true,`
`   `**`user`**`: {`
`       `**`firstname`**`: "Miguel",`
`       `**`lastname`**`: "Fuertes",`
`       `**`email`**`: "hkfuertes@hotmail.com"`
`   }`
`}`

En caso de que la **aplicación no este registrada**, y que por tanto el token de la aplicación sea inválido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de haber algún error, o de que **el token ya no sea válido**, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

En caso de que el usuario del que se pide la información no exista el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 3}`

Creación de un Usuario
----------------------

Creación de un usuario en el servicio web:

    http://<eventus_api>/<app_token>/user/create

Necesita de variables POST con todos los posibles campos:

-   ***username*** con el nombre de usuario.
-   ***password*** con la contraseña.
-   ***firstname*** con el nombre del usuario.
-   ***lastname*** con el apellidos del usuario (o los dos)
-   ***email*** con el email del usuario.

Esto devolverá un JSON de la forma:

`{`
`   `**`success`**`: true,`
`   `**`user`**`: {`
`       `**`username`**`: "username",`
`       `**`token`**`: "88ff2bc4c5e610cf330c667151dbccbb"`
`   }`
`}`

En caso de que la **aplicación no este registrada**, y que por tanto el token de la aplicación sea inválido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de no poder crear el usuario, **porque ya exista**, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

Funciones de Eventos
====================

Recuperar la información de un evento
-------------------------------------

Recuperación la información de un evento de la base de datos de Eventus:

    http://<eventus_api>/<app_token>/event/info/<event_key>

Además del parámetro ***event_key***, requiere de variables POST:

-   ***token*** que contendrá el token recibido al validar el usuario.
-   ***username*** que contendrá el nombre de usuario.

Esto devolverá un JSON de la forma:

`{`
`   `**`success`**`: true,`
`   `**`info`**`: {`
`       `**`name`**`: "Evento",`
`       `**`place`**`: "Lugar",`
`       `**`date`**`: "DD-MM-AAAA",`
`       `**`type`**`: "Tipo",`
`       `**`admin`**`: "admin_username"`
`   },`
`   `**`participants`**`: [`
`       "hkfuertes"`
`   ],`
`   `**`program`**`: [`
`       {`
`           `**`time`**`: "HH:MM:SS",`
`           `**`act`**`: "act1"`
`       },`
`       {`
`           `**`time`**`: "HH:MM:SS",`
`           `**`act`**`: "act2"`
`       }`
`   ]`
`}`

En caso de que la **aplicación no esté registrada**, y que por tanto el token de la aplicación sea invalido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de que el **token** del usuario no sea válido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

En caso de que el evento no exista o no esté activo, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 3}`

En caso de que el usuario no pertenezca al evento, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 4}`

Listar eventos de los que el usuario es Administrador
-----------------------------------------------------

Recuperación del listado de los eventos que administra un usuario:

    http://<eventus_api>/<app_token>/event/list/admin/<who>

Donde ***who*** será el nombre de usuario del que se desea consultar los eventos que administra. Además de este parámetro se requiere de variables POST:

-   ***token*** que contendrá el token recibido al validar el usuario.
-   ***username*** que contendrá el nombre de usuario que hace la petición, o en uso.

Esto devolverá un JSON de la forma:

`{`
`   `**`success`**`: true,`
`   `**`events`**`: {`
`       `***<event_1_key>***`: {`
`           `**`name`**`: "Evento 1",`
`           `**`place`**`: "Lugar",`
`           `**`date`**`: "DD-MM-AAAA",`
`           `**`type`**`: "Tipo",`
`           `**`admin`**`: "admin_username"`
`       }`
`       `***<event_2_key>***`: {`
`           `**`name`**`: "Evento 2",`
`           `**`place`**`: "Lugar",`
`           `**`date`**`: "DD-MM-AAAA",`
`           `**`type`**`: "Tipo",`
`           `**`admin`**`: "admin_username"`
`       }`
`   }`
`}`

En caso de que la **aplicación no esté registrada**, y que por tanto el token de la aplicación sea inválido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de que el **token** del usuario no sea válido, o de que el usuario no exista, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

En caso de que el usuario pedido no exista, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 3}`

Listar eventos a los que pertenece un usuario
---------------------------------------------

Recuperación del listado de los eventos a los que pertenece un usuario:

    http://<eventus_api>/<app_token>/event/participation/<who>

Donde ***who*** será el nombre de usuario del que se desea consultar los eventos a los que pertenece. Además de este parámetro se requiere de variables POST:

-   ***token*** que contendrá el token recibido al validar el usuario.
-   ***username*** que contendrá el nombre de usuario que hace la petición, o en uso.

Esto devolverá un JSON de la forma:

`{`
`   `**`success`**`: true,`
`   `**`events`**`: {`
`       `***<event_1_key>***`: {`
`           `**`name`**`: "Evento 1",`
`           `**`place`**`: "Lugar",`
`           `**`date`**`: "DD-MM-AAAA",`
`           `**`type`**`: "Tipo",`
`           `**`admin`**`: "admin_username"`
`       }`
`       `***<event_2_key>***`: {`
`           `**`name`**`: "Evento 2",`
`           `**`place`**`: "Lugar",`
`           `**`date`**`: "DD-MM-AAAA",`
`           `**`type`**`: "Tipo",`
`           `**`admin`**`: "admin_username"`
`       }`
`   }`
`}`

En caso de que la **aplicación no esté registrada**, y que por tanto el token de la aplicación sea inválido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de que el **token** del usuario no sea valido, o de que el usuario no exista, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

En caso de que el usuario pedido no exista, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 3}`

Asociar usuario a evento
------------------------

Asocia el usuario "en sesión" a un evento:

    http://<eventus_api>/<app_token>/event/join/<event_key>

Se requiere de variables POST:

-   ***token*** que contendrá el token recibido al validar el usuario.
-   ***username*** que contendrá el nombre de usuario que hace la petición, o en uso.

Esto devolverá un JSON de la forma:

`{`
`   `**`success`**`: true,`
`   `**`participation`**`: {`
`       `**`username`**`: "usuario",`
`       `**`eventname`**`: "Evento",`
`       `**`joined_at`**`: "AAAA-MM-DD HH:MM:SS"`
`   }`
`}`

En caso de que la **aplicación no esté registrada**, y que por tanto el token de la aplicación sea inválido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de que el **token** del usuario no sea valido, o de que el usuario no exista, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

En caso de que el evento **no exista** o no este marcado como activo, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 3}`

En caso de que el usuario **ya esté asociado** al evento, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 4}`

Invitar usuarios a un evento
----------------------------

Invita a un numero de usuarios a que se asocien al evento.

    http://<eventus_api>/<app_token>/event/web/join/<event_key>

Esta función no requiere de ningún parámetro POST, puesto que se solicitarán los datos de usuario al seguir el enlace. El funcionamiento de esta función es muy sencillo, el usuario pinchará en el enlace, meterá sus datos y se unirá al evento automáticamente. Está pensado para usarlo como enlace de compartir, por ejemplo, en Android con el Intent "compartir vía" ya que realmente es solo texto y no necesita de ningún formulario.

El sistema pedirá el login y devolverá "JOINED SUCCESSFULLY!!!" en caso de que todo sea correcto, o devolverá error.

En caso de que el evento **no exista** o no este marcado como activo, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

En caso de que el usuario **ya esté asociado** al evento, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 3}`

Desasociar el usuario en uso de un evento
-----------------------------------------

Elimina la asociación del usuario "activo" de un evento:

    http://<eventus_api>/<app_token>/event/remove/<event_key>

Se requiere de variables POST:

-   ***token*** que contendrá el token recibido al validar el usuario.
-   ***username*** que contendrá el nombre de usuario que hace la petición, o en uso.

Esto devolverá un JSON de la forma:

`{`
`   `**`success`**`: true,`
`   `**`participation`**`: {`
`       `**`username`**`: "usuario",`
`       `**`eventname`**`: "Evento",`
`       `**`joined_at`**`: "AAAA-MM-DD HH:MM:SS"`
`   }`
`}`

En caso de que la **aplicación no este registrada**, y que por tanto el token de la aplicación sea inválido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de que el **token** del usuario no sea valido, o de que el usuario no exista, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

En caso de que el evento **no exista** o no este marcado como activo, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 3}`

En caso de que el usuario **no esté asociado al evento**, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 4}`

**IMPORTANTE: SI EL USUARIO QUE SE DESASOCIA DEL EVENTO ES EL ADMINISTRADOR, SE BORRA EL EVENTO Y SE PIERDEN TODAS LAS FOTOS**

Crear o modificar un evento
---------------------------

Elimina la asociación del usuario "activo" de un evento:

    http://<eventus_api>/<app_token>/event/create

Se requiere de variables POST:

-   ***token*** que contendrá el token recibido al validar el usuario.
-   ***username*** que contendrá el nombre de usuario que hace la petición, o en uso.
-   ***event_data\[name\]*** que contendrá el nombre del evento.
-   ***event_data\[place\]*** que contendrá el lugar del evento.
-   ***event_data\[date\]*** que contendrá la fecha del evento.
-   ***event_data\[event_type_id\]*** que contendrá el id del tipo de evento, pudiendo ser ahora mismo:
    -   1 =&gt; Boda
    -   2 =&gt; Bautizo
    -   3 =&gt; Comunión
    -   4 =&gt; Graduación

**Además de un campo event_data\[key\] con la key del evento en cuestión, que se rellenara en caso de que estemos modificando el evento.**

Esto devolverá un JSON de la forma:

`{`
`   "success":true,`
`   "event":{`
`      "key":"97c6d54d08",`
`      "name":"Evento",`
`      "place":"Pamplona",`
`      "date":"13-10-2014",`
`      "type":"Comunion",`
`      "admin":"hkfuertes"`
`   }`
`}`

En caso de que la **aplicación no esté registrada**, y que por tanto el token de la aplicación sea inválido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de que el **token** del usuario no sea válido, o de que el usuario no exista, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

En caso de que la información del evento no se haya pasado, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 3}`

Crear o modificar el programa de actos de un evento
---------------------------------------------------

Elimina la asociación del usuario "activo" de un evento:

    http://<eventus_api>/<app_token>/event/program/update

Se requiere de variables POST:

-   ***token*** que contendrá el token recibido al validar el usuario.
-   ***username*** que contendrá el nombre de usuario que hace la petición, o en uso.
-   ***event_program\[key\]\[\#\]\[time\]*** que contendrá la hora del acto.
-   ***event_program\[key\]\[\#\]\[act\]*** que contendrá el nombre del acto de la hora de la linea anterior.

(\# se ha usado para indicar que ahí va un número)

Esto devolverá un JSON de la forma:

`{`
`   "success":true,`
`   "program":[`
`      {"time":"17:00:00","act":"Cafe"}`
`   ]`
`}`

En caso de que la **aplicación no esté registrada**, y que por tanto el token de la aplicación sea inválido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de que el **token** del usuario no sea valido, o de que el usuario no exista, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

En caso de que la información del evento no se haya pasado, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 3}`

En caso de que el evento no exista o no esté activo, y se quiera modificar el programa sobre él, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 4, `**`event`**`: event_key, `**`entry`**`: entry}`

En caso de que la información del evento no se haya pasado, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 5, `**`event`**`: event_key, `**`entry`**`: entry}`

Funciones de Fotos
==================

Ver fotos de un evento
----------------------

Devuelve una lista con los identificadores de las fotos para pedir al método show.

    http://<eventus_api>/<app_token>/event/photos/<event_key>

Se requiere de variables POST:

-   ***token*** que contendrá el token recibido al validar el usuario.
-   ***username*** que contendrá el nombre de usuario que hace la petición, o en uso.

Esto devolverá un JSON de la forma:

`{`
`   "success":true,`
`   "event_key":"9f9afjpasof9afpoernq80w9fu98y",`
`   "photos":[`
`      {`
`         "username":"hkfuertes",`
`         "filename":"9f9afjpasof9afpoernq80w9fu98y_null_1412174063532.jpg",`
`         "uploaded_at":"2014-10-01 16:34:36",`
`         "photo_id":6`
`      },{`
`         "username":"hkfuertes",`
`         "filename":"9f9afjpasof9afpoernq80w9fu98y_null_1412956599888.jpg",`
`         "uploaded_at":"2014-10-10 17:56:50",`
`         "photo_id":7`
`      }]`
`}`

En caso de que la **aplicación no esté registrada**, y que por tanto el token de la aplicación sea inválido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de que el **token** del usuario no sea valido, o de que el usuario no exista, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

En caso de que el evento **no exista** o no esté marcado como activo, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 3}`

En caso de que el usuario **no esté asociado al evento**, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 4}`

Ver fotos de un usuario
-----------------------

Devuelve una lista con los identificadores de las fotos para pedir al método show.

    http://<eventus_api>/<app_token>/user/photos

Se requiere de variables POST:

-   ***token*** que contendrá el token recibido al validar el usuario.
-   ***username*** que contendrá el nombre de usuario que hace la petición, o en uso.

Esto devolverá un JSON de la forma:

`{`
`   "success":true,`
`   "user":"hkfuertes",`
`   "photos":[`
`      {`
`         "username":"hkfuertes",`
`         "filename":"9f9afjpasof9afpoernq80w9fu98y_null_1412174063532.jpg",`
`         "uploaded_at":"2014-10-01 16:34:36",`
`         "photo_id":6`
`      },{`
`         "username":"hkfuertes",`
`         "filename":"9f9afjpasof9afpoernq80w9fu98y_null_1412956599888.jpg",`
`         "uploaded_at":"2014-10-10 17:56:50",`
`         "photo_id":7`
`      }]`
`}`

En caso de que la **aplicación no esté registrada**, y que por tanto el token de la aplicación sea invalido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de que el **token** del usuario no sea válido, o de que el usuario no exista, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

En caso de que el evento **no exista** o no esté marcado como activo, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 3}`

En caso de que el usuario **no esté asociado al evento**, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 4}`

Subir una foto
--------------

Sube una foto al servidor.

    http://<eventus_api>/<app_token>/event/upload/<event_key>

Se requiere de variables POST:

-   ***token*** que contendrá el token recibido al validar el usuario.
-   ***username*** que contendrá el nombre de usuario que hace la petición, o en uso.
-   ***photo*** que contendrá el fichero a subir (**campo <input type="file"> de html**)

Esto devolverá un JSON de la forma:

`{`
`   "success":true,`
`   "user":"hkfuertes",`
`   "event":"9f9afjpasof9afpoernq80w9fu98y",`
`   "photo":{`
`      "username":"hkfuertes",`
`      "filename":"event_create.png",`
`      "uploaded_at":"2014-10-21 18:03:45",`
`      "photo_id":8`
`   }`
`}`

En caso de que la **aplicación no esté registrada**, y que por tanto el token de la aplicación sea invalido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de que el **token** del usuario no sea valido, o de que el usuario no exista, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`

En caso de que el evento **no exista** o no esté marcado como activo, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 3}`

En caso de que el usuario **no esté asociado al evento**, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 4}`

En caso de error del sistema, se devolverá:

`{`**`success`**`:false, `**`error`**`: 5}`

En caso de que el fichero subido **no sea una imagen**, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 6}`

Peticion de imagen al servidor
------------------------------

Devuelve la imagen pedida.

    http://<eventus_api>/<app_token>/show/photo/<event_key>/<photo_id>

Si no ocurren errores, el sistema devolverá una imagen.

En caso de que la **aplicación no esté registrada**, y que por tanto el token de la aplicación sea inválido, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 1}`

En caso de que el **token** del usuario no sea valido, o de que el usuario no exista, el sistema devolverá:

`{`**`success`**`:false, `**`error`**`: 2}`
