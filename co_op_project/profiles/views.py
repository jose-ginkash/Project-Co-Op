from django.urls import reverse_lazy
from django.views.generic import DetailView, UpdateView
from .models import Profile
from .forms import ProfileForm

class ProfileDetailView(DetailView):
    model = Profile
    template_name = 'profiles/profile_detail.html'
    context_object_name = 'profile'

class ProfileUpdateView(UpdateView):
    model = Profile
    form_class = ProfileForm
    template_name = 'profiles/profile_update.html'

    def get_success_url(self):
        return reverse_lazy('profile-detail', kwargs={'pk': self.object.pk})
