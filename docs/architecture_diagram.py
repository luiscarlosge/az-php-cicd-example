#!/usr/bin/env python3
"""
Architecture Diagram Generator
Generates the CI/CD pipeline architecture diagram for the Cloud Computing Portal
"""

from diagrams import Diagram, Cluster, Edge
from diagrams.azure.compute import AppServices
from diagrams.azure.web import AppServicePlans
from diagrams.azure.general import Resourcegroups
from diagrams.azure.storage import StorageAccounts
from diagrams.onprem.vcs import Github
from diagrams.onprem.ci import GithubActions
from diagrams.programming.language import Php
from diagrams.onprem.client import Users

# Output path (same directory as this script)
import os
script_dir = os.path.dirname(os.path.abspath(__file__))
output_path = os.path.join(script_dir, "architecture")

with Diagram(
    "Cloud Computing Portal - CI/CD Architecture",
    filename=output_path,
    show=False,
    direction="LR",
    graph_attr={
        "fontsize": "14",
        "bgcolor": "white",
        "pad": "0.5"
    }
):
    # Users
    users = Users("Students &\nFaculty")
    
    # GitHub
    with Cluster("GitHub"):
        repo = Github("Repository\n(Source Code)")
        actions = GithubActions("GitHub Actions\n(CI/CD Pipeline)")
        repo >> Edge(label="trigger") >> actions
    
    # Azure Infrastructure
    with Cluster("Azure Cloud"):
        with Cluster("Resource Group\nrg-cloud-portal"):
            # Terraform Backend
            storage = StorageAccounts("Storage Account\n(Terraform State)")
            
            # App Service
            with Cluster("App Service Plan (Linux F1)"):
                webapp = AppServices("Web App\n(PHP 8.2)")
                php = Php("Portal\nApplication")
                webapp - php
    
    # Connections
    actions >> Edge(label="terraform\napply", color="purple") >> storage
    actions >> Edge(label="deploy", color="green") >> webapp
    users >> Edge(label="HTTPS") >> webapp
