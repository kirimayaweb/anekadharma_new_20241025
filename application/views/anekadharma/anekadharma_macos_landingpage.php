<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>PD ANEKADHARMA</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'>
  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css'>
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/anekadharma_macosstyle/style.css">

</head>

<body>
  <!-- partial:index.partial.html -->

  <body>
    <section>

      <div class="icon-container">
        <div class="icon-box about" data-modal="about">
          <i class="fa-regular fa-address-card"></i>
        </div>
        <div class="icon-box projects" data-modal="projects">
          <i class="fa-solid fa-laptop-code"></i>
        </div>
        <div class="icon-box testimonial" data-modal="testimonial">
          <i class="fa-solid fa-users-rectangle"></i>
        </div>
        <div class="icon-box contact" data-modal="contact">
          <i class="fa-solid fa-envelope"></i>
        </div>
      </div>

    </section>

    <div class="popup" id="about">
      <div class="popup-container">
        <div class="popup-header">
          <div class="button-container">
            <button class="close-btn circle-btn red">
              <i class="fa-solid fa-xmark"></i>
            </button>
            <button class="close-btn circle-btn yellow">
              <i class="fa-solid fa-window-minimize"></i>
            </button>
            <button class="maximize-btn circle-btn green">
              <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
            </button>
          </div>
        </div>
        <div class="popup-body about-container">
          <div class="img-frame">
            <img
              src="<?php echo base_url() ?>assets/anekadharma_macosstyle/Bantul_2.png"
              alt="" />
          </div>
          <div class="hero-content">
            <h1>PD Aneka Dharma</h1>
            <p>
              Perusahaan Daerah Aneka Dharma (PD Aneka Dharma) merupakan Badan Usaha Milik Daerah yang mempunyai ruang lingkup kegiatan di bidang perdagangan umum, pelayanan jasa, pertanian, perindustrian, pertambangan, peternakan dan pariwisata. PD Aneka Dharma didirikan sejak tahun 1978 dengan PERDA No. 5 tahun 1978 jo Perda No.1 Tahun 1991 dengan tujuan “Turut serta melaksanakan pembangunan daerah dalam rangka mengembangkan pertumbuhan ekonomi Daerah serta meningkatkan penghasilan daerah untuk mempertinggi taraf hidup rakyat.”
              <br />
              <br />
              <a href=" https://anekadharma.bantulkab.go.id/hal/profil-sejarah-pembentukan" target="_blank" style="color: white;"> https://anekadharma.bantulkab.go.id/hal/profil-sejarah-pembentukan</a>
             

            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="popup" id="projects">
      <div class="popup-container">
        <div class="popup-header">
          <div class="button-container">
            <button class="close-btn circle-btn red">
              <i class="fa-solid fa-xmark"></i>
            </button>
            <button class="close-btn circle-btn yellow">
              <i class="fa-solid fa-window-minimize"></i>
            </button>
            <button class="maximize-btn circle-btn green">
              <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
            </button>
          </div>
        </div>

        <div class="popup-body">
          <div class="skill-list">
            <h1>Products</h1>
            <ul>
              <li>ATK</li>
              <li>Jasa</li>
              <!-- <li>Javascript</li>
              <li>React</li>
              <li>Vue JS</li>
              <li>Bootstrap</li>
              <li>Tailwind CSS</li> -->
            </ul>
          </div>

          <div class="project-container">
            <h1>Projects</h1>
            <div class="all-projects">
              <div class="project-box">
                <img
                  src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/0c183f3d-cc94-4ed9-afea-4d33740dbf40"
                  alt="" />
                <div class="overlay">
                  <h3>ATK</h3>
                  <button class="more-btn">
                    <span>Learn More</span>
                  </button>
                </div>
              </div>

              <div class="project-box">
                <img
                  src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/08c8b66f-7e24-42d9-848d-23e30f7e1968"
                  alt="" />
                <div class="overlay">
                  <h3>Landing Page Template</h3>
                  <button class="more-btn">
                    <span>Learn More</span>
                  </button>
                </div>
              </div>

              <div class="project-box">
                <img
                  src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/d6e74bee-a0f6-416e-98cc-b0985caf507f"
                  alt="" />
                <div class="overlay">
                  <h3>Travel Landing Page</h3>
                  <button class="more-btn">
                    <span>Learn More</span>
                  </button>
                </div>
              </div>

              <div class="project-box">
                <img
                  src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/28946a23-30af-4b36-b30b-e4a3e696aaf6"
                  alt="" />
                <div class="overlay">
                  <h3>Plant Search App</h3>
                  <button class="more-btn">
                    <span>Learn More</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="popup" id="testimonial">
      <div class="popup-container">
        <div class="popup-header">
          <div class="button-container">
            <button class="close-btn circle-btn red">
              <i class="fa-solid fa-xmark"></i>
            </button>
            <button class="close-btn circle-btn yellow">
              <i class="fa-solid fa-window-minimize"></i>
            </button>
            <button class="maximize-btn circle-btn green">
              <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
            </button>
          </div>
        </div>
        <div class="popup-body testimonial-container">
          <h1>Customer</h1>

          <div class="slider-container">
            <div class="swiper">
              <div class="swiper-wrapper">
                <div class="swiper-slide">
                  <div class="user-info">
                    <img
                      src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/c3e8a2a1-ab19-4fae-be0c-92bb3b6f3122"
                      alt="" />
                    <h2>Rumah Sakit Panembahan Senopati</h2>
                  </div>
                  <p>
                    Rumah Sakit Umum Daerah Panembahan Senopati merupakan pendukung penyelenggaraan pemerintah daerah yang dipimpin oleh seorang Direktur yang berkedudukan di bawah dan bertanggungjawab kepada Bupati melalui Sekretaris Daerah.
                  </p>
                </div>

                <div class="swiper-slide">
                  <div class="user-info">
                    <img
                      src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/f54a0d84-8f52-4fa6-886f-0acb5a724130"
                      alt="" />
                    <h2>Pemkab Bantul</h2>
                  </div>
                  <p>
                    Kabupaten Bantul terletak di sebelah Selatan Propinsi Daerah Istimewa Yogyakarta, berbatasan dengan :
                    - Utara : Kota Yogyakarta dan Kabupaten Sleman
                    - Selatan : Samudera Indonesia
                    - Timur : Kabupaten Gunung Kidul
                    - Barat: Kabupaten Kulon Progo
                  </p>
                </div>

                <div class="swiper-slide">
                  <div class="user-info">
                    <img
                      src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/30f2184c-df17-4fd8-aabf-2db7cdad85e4"
                      alt="" />
                    <h2>Margaret Turner</h2>
                  </div>
                  <p>
                    Ruth's ability to transform ideas into visually appealing
                    and user-friendly websites showcases her dedication and
                    talent in the field.
                  </p>
                </div>

                <div class="swiper-slide">
                  <div class="user-info">
                    <img
                      src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/f010b87b-d741-44c2-8985-133b25cce2ea"
                      alt="" />
                    <h2>Ava Mitchell</h2>
                  </div>
                  <p>
                    If you want a website that looks fantastic, Ruth is the
                    person to call. She brings creativity and tech skills
                    together seamlessly.
                  </p>
                </div>

                <div class="swiper-slide swiper-no-swiping">
                  <div class="user-info">
                    <img
                      src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/0ca59a80-3ef2-4f1e-b80a-bf957207838f"
                      alt="" />
                    <h2>Samuel Morgan</h2>
                  </div>
                  <p>
                    Ruth is a frontend genius! Her work is clean, modern, and
                    always leaves a positive impression.
                  </p>
                </div>
              </div>
              <ul class="control" id="custom-control">
                <li class="prev">
                  <ion-icon class="arrow" name="caret-back-outline"></ion-icon>
                </li>
                <li class="next">
                  <ion-icon
                    class="arrow"
                    name="caret-forward-outline"></ion-icon>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="popup" id="contact">
      <div class="popup-container">
        <div class="popup-header">
          <div class="button-container">
            <button class="close-btn circle-btn red">
              <i class="fa-solid fa-xmark"></i>
            </button>
            <button class="close-btn circle-btn yellow">
              <i class="fa-solid fa-window-minimize"></i>
            </button>
            <button class="maximize-btn circle-btn green">
              <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
            </button>
          </div>
        </div>
        <div class="popup-body contact-container">
          <h1>ANEKA DHARMA Apps</h1>
          <!-- <p>Silahkan login untuk masuk ke aplikasi.</p> -->

          <?php
          $status_login = $this->session->userdata('status_login');
          if (empty($status_login)) {
            $message = "Silahkan login untuk masuk ke aplikasi";
          } else {
            $message = $status_login;
          }
          ?>

          <p class="login-box-msg"><?php echo $message; ?></p>
          <?php echo form_open('Anekadharmamasuk/cheklogin'); ?>

          <label for="email">Username</label>
          <input
            type="text"
            id="email"
            name="email"
            placeholder="Username.." required/>

          <label for="password">Password</label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Password.." required />




            
          <!-- <label for="subject">Subject</label>
            <textarea
              id="subject"
              name="subject"
              placeholder="Write something.."
              style="height: 200px"></textarea> -->

          <button class="submit-btn more-btn" type="submit">
            <span>Login <i class="fa-solid fa-paper-plane"></i></span>
            <!-- <span>Lupa Password <i class="fa-solid fa-paper-plane"></i></span> -->
          </button>
          </form>

          <?php echo form_open('Anekadharmamasuk/forgotpassword'); ?>
          <button class="submit-btn more-btn" type="submit">
            <span>Lupa Password <i class="fa-solid"></i></span>
          </button>

          </form>



        </div>
      </div>
    </div>
  </body>
  <!-- partial -->
  <script src='https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js'></script>
  <script src='https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js'></script>
  <script src='https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js'></script>
  <script src="<?php echo base_url() ?>assets/anekadharma_macosstyle/script.js"></script>

</body>

</html>