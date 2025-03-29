from django.urls import path
from .views import ProfileDetailView, ProfileUpdateView

urlpatterns = [
    path('<int:pk>/', ProfileDetailView.as_view(), name='profile-detail'),
    path('<int:pk>/edit/', ProfileUpdateView.as_view(), name='profile-update'),
]