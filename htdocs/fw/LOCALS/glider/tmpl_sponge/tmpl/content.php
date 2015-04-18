   <div class="row">
    <nav class="top-bar" data-topbar>
        <ul class="title-area">
            <li class="name">
                <h1>
                    <a href="/">Sponge Hack Days</a>
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
                    <a href="/faq">
                        FAQ
                    </a>
                </li>
                <li class="active">
                    <a href="/projects">
                        Projects list
                    </a>
                </li>
                <li class="active">
                    <a href="/schedule">
                        Schedule
                    </a>
                </li>
                <li class="active">
                    <a href="/about">
                        About
                    </a>
                </li>
                <li class="has-dropdown">
                    <a href="#">
                        Resources
                    </a>
                    <ul class="dropdown">
                        <li>
                            <a href="http://thesponge.eu" target="_blank">
                                The Sponge website
                            </a>
                        </li>
                    </ul>
                </li>
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


