<h1 style="color: #96580d87;">
  Brew & Co.
  <span style="font-size: 14px; font-weight: normal; color: #e9a7617c;">
    · Aplicació Web de Cafeteria
  </span>
</h1>

<h3 style="border-bottom: 1px solid #7d6d5c; color: #9a6935cc;">
  Concepte General
</h3>

<p>
Brew & Co. és una aplicació web completa per a una cafeteria moderna. L'objectiu és oferir als clients una experiència digital atractiva on puguin consultar el catàleg de productes, realitzar comandes en línia i fer el seguiment dels seus enviaments en temps real.
</p>

<p>
L'aplicació inclou també un panell d'administració per gestionar productes, comandes i analitzar dades de vendes amb suport d'intel·ligència artificial.
</p>

<h3 style="border-bottom: 1px solid #7d6d5c; color: #a08e7b;">
  Stack Tecnològic
</h3>

<table>
  <thead>
    <tr>
      <th>Capa</th>
      <th>Tecnologia</th>
      <th>Justificació</th>
    </tr>
  </thead>
  <tbody style="text-align: center;">
    <tr style="background-color: #d4d4d433;">
      <td>Frontend</td>
      <td>React</td>
      <td>Modern i àmpliament utilitzat</td>
    </tr>
    <tr>
      <td>Estils</td>
      <td>CSS + Bootstrap</td>
      <td>Disseny àgil i posicionament robust</td>
    </tr>
    <tr style="background-color: #d4d4d433;">
      <td>Backend</td>
      <td>Laravel (PHP)</td>
      <td>ORM Eloquent, autenticació integrada i robustesa</td>
    </tr>
    <tr>
      <td>Base de dades</td>
      <td>MySQL</td>
      <td>Model relacional adequat per a les entitats</td>
    </tr>
    <tr style="background-color: #d4d4d433;">
      <td>Contenidors</td>
      <td>Docker</td>
      <td>Entorn reproduïble per desplegament</td>
    </tr>
  </tbody>
</table>

<h3 style="border-bottom: 1px solid #7d6d5c; color: #a08e7b;">
  Esquema de la Base de Dades
    <span style="font-size: 14px; font-weight: normal; color: #e9a7617c;">
    · (Pot canviar)
  </span>
</h3>

<pre style="line-height: 1.6;">

<div><strong style="color: #c08a4d;">usuaris</strong> — id, nom, email, contrasenya, rol, created_at</div>

<div><strong style="color: #c08a4d;">adreces</strong> — id, <span style="color:#4fc3f7;">usuari_id</span>, etiqueta, adreça, codi_postal, ciutat, predeterminada, created_at</div>

<div><strong style="color: #c08a4d;">categories</strong> — id, nom, descripcio, activa</div>

<div><strong style="color: #c08a4d;">productes</strong> — id, <span style="color:#81c784;">categoria_id</span>, nom, descripcio, preu, imatge, disponible, destacat</div>

<div><strong style="color: #c08a4d;">ofertes</strong> — id, <span style="color:#ffb74d;">producte_id</span>, preu_rebaixat, data_inici, data_fi</div>

<div><strong style="color: #c08a4d;">codis_descompte</strong> — id, codi, percentatge, actiu, expires_at</div>

<div><strong style="color: #c08a4d;">comandes</strong> — id, <span style="color:#4fc3f7;">usuari_id</span>, <span style="color:#ba68c8;">adreca_id</span>, estat, total, created_at</div>

<div><strong style="color: #c08a4d;">linies_comanda</strong> — id, <span style="color:#e57373;">comanda_id</span>, <span style="color:#ffb74d;">producte_id</span>, quantitat, preu_unitari</div>

<div><strong style="color: #c08a4d;">pagaments</strong> — id, <span style="color:#e57373;">comanda_id</span>, metode, estat, <span style="color:#ffd54f;">codi_descompte_id</span> (nullable), created_at</div>

</pre>

<br>

<blockquote>
<b style="color:#4fc3f7;">Blau</b> → Relació amb <b>usuaris</b><br>
<b style="color:#81c784;">Verd</b> → Relació amb <b>categories</b><br>
<b style="color:#ffb74d;">Taronja</b> → Relació amb <b>productes</b><br>
<b style="color:#ba68c8;">Lila</b> → Relació amb <b>adreces</b><br>
<b style="color:#e57373;">Vermell</b> → Relació amb <b>comandes</b><br>
<b style="color:#ffd54f;">Groc</b> → Relació amb <b>codis_descompte</b>
</blockquote>

<h4 style="color: #a08e7b;">
  Esquema Visual
</h4>

<p><i>Pròximament</i></p>

<h3 style="border-bottom: 1px solid #7d6d5c; color: #a08e7b;">
  Funcionalitats
</h3>

<h4>4.1 Pàgina Principal</h4>
<ul>
  <li>Disseny d'una sola pàgina amb scroll horitzontal</li>
  <li>Animacions fade in/out amb JavaScript</li>
  <li>Visualització parcial del catàleg amb bloqueig per login</li>
  <li>Productes populars actualitzats setmanalment</li>
</ul>

<h4>4.2 Autenticació</h4>
<ul>
  <li>Registre amb validació de camps</li>
  <li>Login amb JWT (Laravel Sanctum)</li>
  <li>Rols: client i administrador</li>
</ul>

<h4>4.3 Catàleg i Comandes</h4>
<ul>
  <li>Filtrat de productes per categoria</li>
  <li>Ofertes diàries</li>
  <li>Comandes en estat "pendent"</li>
  <li>Cancel·lació abans de processar</li>
  <li>Pagament amb targeta, descomptes i efectiu</li>
  <li>API de pagament simulada (èxit/error aleatori)</li>
  <li>Control manual d'estat (admin)</li>
</ul>

<h4>4.4 Seguiment en Temps Real</h4>
<ul>
  <li><b>Empaquetant:</b> 1–5 minuts (temps aleatori)</li>
  <li><b>En enviament:</b> 5–30 minuts (estimació visible)</li>
  <li>Visualització de producte, preu, adreça i estat</li>
</ul>

<h4>4.5 Panell d'Administració</h4>
<ul>
  <li>Registre complet de comandes</li>
  <li>Exportació CSV i JSON</li>
  <li>Gestió de productes i ofertes</li>
  <li>Gràfics setmanals (vendes i ingressos)</li>
  <li>Xat intern amb IA</li>
</ul>

<h4>4.6 Intel·ligència Artificial</h4>
<p>
El panell inclou un assistent d'IA (Claude, GPT o similar) per fer consultes en llenguatge natural sobre les dades de vendes.
</p>

<blockquote>
Exemples:
<br>
— "Quins productes s'han venut més aquesta setmana?"
<br>
— "Quin dia tenim menys comandes?"
</blockquote>