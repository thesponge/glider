   <div class="row">
    <nav class="top-bar" data-topbar>
        <ul class="title-area">
            <li class="name">
                <h1>
                    <a href="/bucuresti">Open Data Hackathon | București</a>
                </h1>
            </li>
            <li class="toggle-topbar menu-icon">
                <a href="#">Menu</a>
            </li>
        </ul>
        <section class="top-bar-section">
            <!-- Right Nav Section -->
            <ul class="right">
                <li class="active">
                    <a href="faq">
			Datele problemei
                    </a>
                </li>
                <li class="active">
                    <a href="proiecte">
                        Proiecte
                    </a>
                </li>
                <li class="active">
                    <a href="program">
                        Cum facem
                    </a>
                </li>
                <li class="active">
                    <a href="despre">
                        Povestea
                    </a>
                </li>
                <li class="active">
                    <a href="organizatori">
                        Organizatori
                    </a>
                </li>
		<!--
                <li class="has-dropdown">
                    <a href="#">
                        Resurse
                    </a>
                    <ul class="dropdown">
                        <li>
                            <a href="http://date.gov.ro" target="_blank">
                                date.gov.ro
                            </a>
                        </li>
                    </ul>
                </li>
		-->
            </ul>
            <!-- Left Nav Section -->
            <!--
            <ul class="left">
                <li>
                    <a href="#">
                        Left Nav Button
                    </a>
                </li>
            </ul> -->
        </section>
    </nav>
   </div>
    <!-- end topbar -->

   <div class="row" style="padding-top: 50px; min-height: 500px;">
      <div class="large-12 columns">
            <?php
                echo $core->Render_Module($core->feedback);
                $obName = $core->mgrName;
                echo $core->Handle_Render($core->$obName);
             ?>
             <!--
             <div class="SING single" id="single_0_en">
                 <div class='EDtxa pageContent' style="min-height: 200px;"></div>
             </div>
             -->
  </div>
  </div>


