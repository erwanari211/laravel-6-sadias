{{-- Page break avoid --}}
<p><strong>Using page break avoid</strong></p>
@for ($i = 1; $i <= 10; $i++)
  <div class="page-break-avoid">
    <div style="background-color: #ccc; min-height: 200px; margin-bottom: 20px; padding: 16px;">
      content {{ $i }} (page-break-avoid)
      <br>
      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero earum, molestias aliquam, quo repellendus perferendis consequuntur facilis fugit quibusdam quisquam dicta at veritatis animi commodi dolorum ab nihil sapiente quas dolor maxime eos? Iure, labore, quod! Distinctio laborum porro deleniti quia ipsam neque architecto, ad. Cum laudantium eius dolor fugiat saepe. Quam officiis, voluptate! Nesciunt doloremque delectus deleniti a, dicta vitae molestiae labore. Magnam ab soluta aut quia deserunt eum, necessitatibus cum cupiditate architecto impedit non perferendis, commodi ut placeat eaque perspiciatis similique maiores temporibus tempore animi! Nostrum quis fugit adipisci, architecto incidunt non, necessitatibus. Velit repudiandae illum cum praesentium iusto voluptatum eum, ipsam facilis ab laudantium delectus dicta quis rem perferendis deleniti voluptates blanditiis iste quam aperiam rerum doloremque. Iusto nesciunt inventore blanditiis reiciendis reprehenderit, aliquam, dicta enim unde consequuntur asperiores debitis dolorum maxime. Provident inventore molestiae eum sint mollitia, doloribus. Ratione enim non, nesciunt neque delectus. Maiores, sapiente.
    </div>
  </div>
@endfor

<div class="page-break"></div>

<p><strong>Without page break avoid</strong></p>
@for ($i = 1; $i <= 10; $i++)
  <div class="">
    <div style="background-color: #ccc; min-height: 200px; margin-bottom: 20px; padding: 16px;">
      content {{ $i }} (normal)
      <br>
      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero earum, molestias aliquam, quo repellendus perferendis consequuntur facilis fugit quibusdam quisquam dicta at veritatis animi commodi dolorum ab nihil sapiente quas dolor maxime eos? Iure, labore, quod! Distinctio laborum porro deleniti quia ipsam neque architecto, ad. Cum laudantium eius dolor fugiat saepe. Quam officiis, voluptate! Nesciunt doloremque delectus deleniti a, dicta vitae molestiae labore. Magnam ab soluta aut quia deserunt eum, necessitatibus cum cupiditate architecto impedit non perferendis, commodi ut placeat eaque perspiciatis similique maiores temporibus tempore animi! Nostrum quis fugit adipisci, architecto incidunt non, necessitatibus. Velit repudiandae illum cum praesentium iusto voluptatum eum, ipsam facilis ab laudantium delectus dicta quis rem perferendis deleniti voluptates blanditiis iste quam aperiam rerum doloremque. Iusto nesciunt inventore blanditiis reiciendis reprehenderit, aliquam, dicta enim unde consequuntur asperiores debitis dolorum maxime. Provident inventore molestiae eum sint mollitia, doloribus. Ratione enim non, nesciunt neque delectus. Maiores, sapiente.
    </div>
  </div>
@endfor
